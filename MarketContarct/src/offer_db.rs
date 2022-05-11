use crate::*;

#[near_bindgen]
impl Contract {
    //Публикация
    #[payable]
    pub fn nft_on_approve(&mut self, token_id: &TokenId, approval_id: u64, msg: String) {

        //measure the initial storage being used on the contract
        let initial_storage_usage = env::storage_usage();

        //Берем цену, которую указал продавец токена
        let master_data: MasterData =
            serde_json::from_str(&msg).expect("nft_on_approve::Error in msg in nft_on_transfer");
        let n_p: u128 = master_data
            .price
            .parse()
            .expect("nft_on_approve::Error in price setting");
        //let new_price = convert_to_yocto(n_p);
        let new_price = n_p;
        let sailer = env::signer_account_id();
        //Формируем новое предложение о продаже
        let new_sail_announcement: Announcement = Announcement {
            sailer: sailer.clone(),
            price: new_price,

            approval_id: approval_id,
        };
        //Обновляем таблицу предложений о продаже
        self.update_offer_db(&token_id, &new_sail_announcement, &sailer);
        //Проверяем, есть ли предложения о покупке
        if let Some(_) = self.demand_token_ind.get(&token_id) {
            //Берем максимальную цену о покупке
            let max_bid = self.find_max_bid_for_token_id(&token_id).expect(
                "nft_on_approve:: there is no max_bid for this tokenId. Seems like arch error",
            );
            //Если максимальная цена больше или равна (не меньше) той, по которой сделал предложение продавец
            if max_bid >= new_price {
                //Берем все предложения с максимальной ценой
                let demands_vec = self
                    .find_demands_set_with_bid_of(&max_bid, &token_id)
                    .expect(
                    "nft_on_approve:: there is no demand set for this bid. Seems like arch error",
                );
                //env::log_str(demands_set.to_vec()[0].to_string().as_str());
                if !demands_vec.is_empty() {
                    //Берем самое раннее предложение
                    let min_demand_id = self.find_min_demand_id_in(&demands_vec);
                    //Совершаем сделку по найденному id предложения о покупке
                    self.make_the_deal_for(&min_demand_id);
                } else {
                    env::log_str("set is empty?");
                    env::log_str(max_bid.to_string().as_str());
                }
            }
        }
        //calculate the required storage which was the used - initial
        let required_storage_in_bytes = env::storage_usage() - initial_storage_usage;

        //refund any excess storage if the user attached too much. Panic if they didn't attach enough to cover the required.
        refund_deposit(required_storage_in_bytes, Some(env::signer_account_id()));
    }

    pub fn take_off_sale(&mut self, token_id: &TokenId, approval_id: u64, remover: AccountId) {
        if let Some(offer) = self.offer.get(&token_id) {
            assert_eq!(
                offer.sailer, remover,
                "take_of_sale:: Only orig sailer can take off the sale"
            );
            assert_eq!(
                offer.approval_id, approval_id,
                "take_of_sale:: Approval ids must be equal"
            );
            self.offer.remove(&token_id);
            self.delete_from_offer_acc_ind_for(&token_id, &offer.sailer);
        }
    }
}

#[near_bindgen]
impl Contract {
    fn update_offer_db(
        &mut self,
        token_id: &TokenId,
        announcement: &Announcement,
        sailer: &AccountId,
    ) {
        self.offer.insert(&token_id, &announcement);
        self.update_offer_acc_ind(&sailer, &token_id);
    }
    fn update_offer_acc_ind(&mut self, account_id: &AccountId, token_id: &TokenId) {
        if let Some(set) = self.offer_acc_ind.get(&account_id) {
            let mut n_s = set;
            self.offer_acc_ind.remove(&account_id);
            n_s.insert(&token_id);
            self.offer_acc_ind.insert(&account_id, &n_s);
        } else {
            let mut n_s: UnorderedSet<TokenId> =
                UnorderedSet::new(account_id.try_to_vec().unwrap());
            n_s.insert(&token_id);
            self.offer_acc_ind.insert(&account_id, &n_s);
        }
    }
    fn find_demands_set_with_bid_of(
        &self,
        bid: &Balance,
        token_id: &TokenId,
    ) -> Option<Vec<DemandId>> {
        if let Some(set) = self.demand_token_ind.get(&token_id) {
            let mut r_s: Vec<DemandId> = Vec::new();
            for demand_id in set.iter() {
                if self
                    .demand
                    .get(&demand_id)
                    .expect(
                        "find_demands_set_with_bid_of::there is no such demand for this demanid",
                    )
                    .price
                    == bid.clone()
                {
                    let b = r_s.push(demand_id.clone());
                }
            }
            return Some(r_s);
        }

        return None;
    }
    fn find_min_demand_id_in(&self, demand_vec: &Vec<DemandId>) -> DemandId {
        let mut min = demand_vec[0];
        for i in demand_vec.iter() {
            if i < &min {
                min = i.clone();
            }
        }
        return min;
    }
    #[private]
    pub fn delete_from_offer_acc_ind_for(&mut self, token_id: &TokenId, account_id: &AccountId) {
        if let Some(set) = self.offer_acc_ind.get(&account_id) {
            let mut _s: UnorderedSet<TokenId> = UnorderedSet::new(b"index".try_to_vec().unwrap());
            for t_id in set.iter() {
                if t_id != token_id.clone() {
                    _s.insert(&t_id);
                }
            }
            self.offer_acc_ind.remove(&account_id);
            self.offer_acc_ind.insert(&account_id, &_s);
        }
    }

    fn find_max_bid_for_token_id(&self, token_id: &TokenId) -> Option<Balance> {
        let mut max_bid = 0;
        if let Some(set_of_demand_id) = self.demand_token_ind.get(&token_id) {
            for demand_id in set_of_demand_id.iter() {
                let demand = self
                    .demand
                    .get(&demand_id)
                    .expect("find_max_bid_for_token_id::no demand?");
                if demand.price > max_bid {
                    max_bid = demand.price;
                }
            }
            return Some(max_bid);
        }
        return None;
    }
    
}








































#[cfg(all(test, not(target_arch = "wasm32")))]
mod tests {
    use super::*;
    use near_sdk::EpochHeight;
    use near_sdk::MockedBlockchain;
    use near_sdk::{testing_env, VMContext};
    use std::collections::HashMap;
    use std::iter::FromIterator;

    fn get_context(predecessor_account_id: AccountId) -> VMContext {
        get_context_with_epoch_height(predecessor_account_id, 0)
    }

    fn get_context_with_epoch_height(
        predecessor_account_id: AccountId,
        epoch_height: EpochHeight,
    ) -> VMContext {
        let acc: String = predecessor_account_id.to_string();
        VMContext {
            current_account_id: "market2.vqq1.testnet".to_string(),
            signer_account_id: "fg10.testnet".to_string(),
            signer_account_pk: vec![0, 1, 2],
            predecessor_account_id: acc,
            input: vec![],
            block_index: 0,
            block_timestamp: 0,
            account_balance: 0,
            account_locked_balance: 0,
            storage_usage: 1000,
            attached_deposit: 0,
            prepaid_gas: 2 * 10u64.pow(14),
            random_seed: vec![0, 1, 2],
            is_view: false,
            output_data_receivers: vec![],
            epoch_height,
        }
    }

    #[test]
    fn it_finds_min() {
        let mut researched_set: UnorderedSet<DemandId> =
            UnorderedSet::new(b"a".try_to_vec().unwrap());
        for i in 1..600 {
            let u_i = i as u128;
            researched_set.insert(&u_i);
        }
        let context = get_context("fg10.testnet".parse().unwrap());
        let mut contract = Contract::new();

        //assert_eq!(1, contract.find_min_demand_id_in(&researched_set));
    }
}
