use crate::*;

#[near_bindgen]
impl Contract {
    //Публикация
    pub fn nft_on_approve(&mut self, token_id: &TokenId, approval_id: u64, msg: String) {
        //Get price from master account
        let masterData: MasterData =
            serde_json::from_str(&msg).expect("nft_on_approve::Error in msg in nft_on_transfer");
        let n_p: u128 = masterData
            .price
            .parse()
            .expect("nft_on_approve::Error in price setting");
        let new_price = convert_to_yocto(n_p);

        let sailer = env::current_account_id();

        let new_sail_announcement: Announcement = Announcement {
            sailer: sailer.clone(),
            price: new_price,

            approval_id: approval_id,
        };

        if let Some(demand_set) = self.demand_token_ind.get(&token_id) {
            let max_bid = self.max_demand_bid.get(&token_id).expect(
                "nft_on_approve:: there is no max_bid for this tokenId. Seems like arch error",
            );
            if max_bid >= new_price {
                let demands_set = self
                    .find_demands_set_with_bid_of(&max_bid, &token_id)
                    .expect(
                    "nft_on_approve:: there is no demand set for this bid. Seems like arch error",
                );
                let min_demand_id = self.find_min_demand_id_in(&demand_set);

                self.make_the_deal_for(&min_demand_id);

                return;
            }
        }
        //Если нет предложений по этому токену (aka Первая публикация на продажу)
        self.public_first_offer(&token_id, &new_sail_announcement, &sailer);
    }
}

#[near_bindgen]
impl Contract {
    fn public_first_offer(
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
    fn find_demands_set_with_bid_of(&self, bid: &Balance, token_id: &TokenId,) -> Option<UnorderedSet<DemandId>> {
        if let Some(set) = self.demand_token_ind.get(&token_id) {
            let mut r_s: UnorderedSet<DemandId> = UnorderedSet::new(token_id.try_to_vec().unwrap());
            for demand_id in set.iter() {
                if self
                    .demand
                    .get(&demand_id)
                    .expect(
                        "find_demands_set_with_bid_of::there is no such demand for this demanid",
                    )
                    .price == bid.clone()
                {
                    r_s.insert(&demand_id);
                }
            }
            return Some(r_s);
        }

        return None;
    }
    fn find_min_demand_id_in(&self, demand_set: &UnorderedSet<DemandId>) -> DemandId {
        let mut min = demand_set.to_vec()[0];
        for i in demand_set.iter() {
            if i < min {
                min = i;
            }
        }
        return min;
    }

    #[payable]
    fn make_the_deal_for(&mut self, demand_id: &DemandId) {

    }
}


#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct OfferJSON {
    pub token_id: TokenId,
    pub sailer: AccountId,
    pub price: Balance,
    pub approval_id: u64,
}

#[near_bindgen]
impl Contract {

    pub fn get_list_of_offers(&self) -> Vec<OfferJSON>{
        let mut return_value: Vec<OfferJSON> = Vec::new();

        for k_v in self.offer.iter() {
            let token_id = k_v.0;
            let announcement = k_v.1;

            let newSailAnnouncement = OfferJSON {
                token_id: token_id,
                sailer: announcement.sailer,
                price: announcement.price,
                approval_id: announcement.approval_id,
            };

            return_value.push(newSailAnnouncement);
        }
        return return_value;
    }
}





#[cfg(all(test, not(target_arch = "wasm32")))]
mod tests {
    use super::*;
    use near_sdk::MockedBlockchain;
    use near_sdk::EpochHeight;
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
        let mut researched_set:UnorderedSet<DemandId> = UnorderedSet::new(b"a".try_to_vec().unwrap());
        for i in 1..600 {
            let u_i = i as u128;
            researched_set.insert(&u_i);
        }
        
        let context = get_context("fg10.testnet".parse().unwrap());
        let mut contract = Contract::new();

        assert_eq!(1, contract.find_min_demand_id_in(&researched_set));

    }
}