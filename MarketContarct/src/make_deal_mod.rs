use crate::*;

pub const GAS_FOR_COMMON_OPERATIONS: Gas = Gas(30_000_000_000_000);
pub const GAS_RESERVED_FOR_CURRENT_CALL: Gas = Gas(20_000_000_000_000);
pub const NFT_ACC: &str = "supremes_nft.testnet";


#[ext_contract(ext_self)]
pub trait Market {
    fn on_nft_transfer(&mut self, token_id: TokenId, demand_id: DemandId);
}

#[ext_contract(ext_nft)]
pub trait NFT {
    fn nft_transfer(receiver_id: AccountId, token_id: String,  approval_id: u64,);

    fn nft_transfer_payout(receiver_id: AccountId, token_id: TokenId, approval_id: u64, balance: Balance, max_len_payout: u32, memo: Option<String>,) -> Payout;
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

        
        // let nft_transfer_call =  ext_nft::nft_transfer(
        //     reciever.clone(),
        //     token_id.clone(),
        //     approval_id.clone(),
        //     NFT_ACC.parse().unwrap(),
        //     1,                   
        //     GAS_FOR_COMMON_OPERATIONS
        // );

        let nft_transfer_call = ext_nft::nft_transfer_payout(
            reciever.clone(),
            token_id.clone(),
            approval_id.clone(),
            self.demand.get(&demand_id).expect("make_the_deal_for:: No such demand_id").price,
            5,
            None,
            NFT_ACC.parse().unwrap(),
            1,                   
            GAS_FOR_COMMON_OPERATIONS
        );
        let REMAINING_GAS: Gas = env::prepaid_gas() - env::used_gas() - GAS_FOR_COMMON_OPERATIONS - GAS_RESERVED_FOR_CURRENT_CALL;
        env::log_str(serde_json::to_string(&REMAINING_GAS).unwrap().as_str());
        let self_callback = ext_self::on_nft_transfer(token_id.clone(), demand_id.clone(), env::current_account_id(), 0, REMAINING_GAS);

        nft_transfer_call.then(self_callback);
    }

    #[private]
    pub fn on_nft_transfer(&mut self, token_id: TokenId, demand_id: DemandId) {
        
        //let succeeded = is_promise_success();

        match env::promise_result(0) {
            PromiseResult::NotReady => unreachable!(),
            PromiseResult::Failed => env::log_str(&"NFT transfer error"),
            PromiseResult::Successful(result) => {
                env::log_str(&"NFT transfer completed successfully");

                //Получаем объект payout, где указаны royalty
                let payout:Payout = near_sdk::serde_json::from_slice::<Payout>(&result).unwrap();

                let user = self.offer.get(&token_id).expect("on_nft_transfer:: there is no such offer").sailer;
                let all_balance = self.demand.get(&demand_id).expect("pay_at_played_bet:: there is no such demandId").price;

                //Посчитаем сумму необходимую выплатить по royalty
                let mut royalty_pay: u128 = 0;
                for (acc_id, proc) in payout.payout.iter() {
                    royalty_pay =  royalty_pay + proc;
                }
                env::log_str(serde_json::to_string(&royalty_pay).unwrap().as_str());
                
                //Итого сумма к выплате продавцу
                //Здесь можно дополнительно учетсь (вычесть из полной стоимости) выплаты другим участникам (прогнать, через проценты)
                let balance_to_user = all_balance - royalty_pay;

                //Переведем продавцу деньги за токен
                if self.pay_at_bet(&user, balance_to_user) {
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
                
                //Переведем деньги всем по roaylaty (скорее всего там один человек, но на всякий случай)
                for (acc_id, proc) in payout.payout.iter() {
                    assert_eq!(self.pay_at_bet(&acc_id, proc.clone()), true, "Error in royalty payment") 
                }
            },
        }
    }

    #[private]
    #[payable]
    pub fn pay_at_bet(&mut self, to: &AccountId, balance: Balance) -> bool {


        Promise::new(to.clone()).transfer(balance);

        return true
    }
}