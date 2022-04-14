use crate::*;

pub const GAS_FOR_COMMON_OPERATIONS: Gas = Gas(30_000_000_000_000);
pub const GAS_RESERVED_FOR_CURRENT_CALL: Gas = Gas(20_000_000_000_000);
pub const NFT_ACC: &str = "fg10.testnet";


#[ext_contract(ext_self)]
pub trait Market {
    fn on_nft_transfer(&mut self, token_id: TokenId, demand_id: DemandId);
}

#[ext_contract(ext_nft)]
pub trait NFT {
    fn nft_transfer(receiver_id: AccountId, token_id: String,  approval_id: u64,);
}

fn is_promise_success() -> bool {
    assert_eq!(env::promise_results_count(),1,"Contract expected a result on the callback");
    match env::promise_result(0) {PromiseResult::Successful(_) => true, _ => false}
}

#[near_bindgen]
impl Contract {

    #[payable]
    #[private]
    //Совершаем сделку по предложению о покупке demand_id
    pub fn make_the_deal_for(&mut self, demand_id: &DemandId) {
        
        //Получаем пользователя, котороу будем продавать токен
        let reciever = self.demand.get(&demand_id).expect("make_the_deal_for::No such token").buyer_acc;
        //Получаем сам token_id
        let token_id = self.demand.get(&demand_id).expect("make_the_deal_for::No such token").token_id;
        //Получаем approval_id для токена
        let approval_id = self.offer.get(&token_id).expect("make_the_deal_for::No such token").approval_id;

        
        let nft_transfer_call =  ext_nft::nft_transfer(
            reciever.clone(),
            token_id.clone(),
            approval_id.clone(),
            NFT_ACC.parse().unwrap(),
            1,                   
            GAS_FOR_COMMON_OPERATIONS
        );
        let REMAINING_GAS: Gas = env::prepaid_gas() - env::used_gas() - GAS_FOR_COMMON_OPERATIONS - GAS_RESERVED_FOR_CURRENT_CALL;

        let self_callback = ext_self::on_nft_transfer(token_id.clone(), demand_id.clone(), env::current_account_id(), 0, REMAINING_GAS);

        nft_transfer_call.then(self_callback);
    }

    #[private]
    pub fn on_nft_transfer(&mut self, token_id: TokenId, demand_id: DemandId) {
        let succeeded = is_promise_success();
        if succeeded {
            env::log_str(&"NFT transfer completed successfully");
 
            //Перевод денег по demand_id
                
            if self.pay_at_bet(&demand_id) {
                env::log_str(&"Money transfer completed successfully");
                //Удаляем offer по которому совершается покупка
                let sailer = self.offer.get(&token_id).expect("on_nft_transfer::there is no such token_id").sailer;
                self.offer.remove(&token_id);
                self.delete_from_offer_acc_ind_for(&token_id, &sailer);

                //Удаляем сыгравшее предложение из таблиц demand
                self.remove_demand_id_from_demand_token_id(&token_id, &demand_id);
                self.remove_demand_id_from_demand_acc_id(&sailer, &demand_id);

                self.demand.remove(&demand_id);
            } 
        }
    }

    #[private]
    #[payable]
    pub fn pay_at_bet(&mut self, demand_id: &DemandId) -> bool {

        let user = self.demand.get(&demand_id).expect("pay_at_played_bet:: there is no such demandId").buyer_acc;
        let balance = self.demand.get(&demand_id).expect("pay_at_played_bet:: there is no such demandId").price;

        Promise::new(user).transfer(balance);

        return true
    }
}