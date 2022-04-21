use crate::*;

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct DemandJSON {
    pub demand_id: DemandId,
    pub buyer_acc: AccountId,
    pub price: Balance,

    pub token_id: TokenId,
}

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct ListOfTokens {
    pub list_of_tokens_id: Vec<TokenId>,
}

#[near_bindgen]
impl Contract {
    pub fn get_list_of_demands(&self) -> Vec<DemandJSON> {
        let mut return_value: Vec<DemandJSON> = Vec::new();

        for k_v in self.demand.iter() {
            let demand_id = k_v.0;
            let demand = k_v.1;

            let demand = DemandJSON {
                demand_id: demand_id,
                buyer_acc: demand.buyer_acc,
                price: demand.price,
                token_id: demand.token_id,
            };

            return_value.push(demand);
        }
        return return_value;
    }

    pub fn get_list_of_demands_for_list_of_token_ids(&self,token_ids: &String,) -> Vec<(TokenId, u128)> {
        let _data: ListOfTokens =
            serde_json::from_str(&token_ids).expect("nft_on_approve::Error in msg in nft_on_transfer");
        
        let vec_of_token_ids = _data.list_of_tokens_id;

        let mut r_v: Vec<(TokenId, u128)> = Vec::new();
        for token_id in vec_of_token_ids.iter() {
            if let Some(set_of_demand) = self.demand_token_ind.get(&token_id) {
                r_v.push((token_id.clone(), set_of_demand.len() as u128))
            }
        }
        return r_v;
    }
}

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct DemandTokenIndJson {
    pub token_id: TokenId,
    pub demand_id: Vec<DemandId>,
}
#[near_bindgen]
impl Contract {
    pub fn get_list_of_demand_token_ind(&self) -> Vec<DemandTokenIndJson> {
        let mut return_value: Vec<DemandTokenIndJson> = Vec::new();

        for k_v in self.demand_token_ind.iter() {
            let token_id = k_v.0;
            let set = k_v.1;

            let demand = DemandTokenIndJson {
                token_id: token_id,
                demand_id: set.to_vec(),
            };

            return_value.push(demand);
        }
        return return_value;
    }
}

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct DemandBuyerIndJson {
    pub buyer: AccountId,
    pub demand_id: Vec<DemandId>,
}
#[near_bindgen]
impl Contract {
    pub fn get_list_of_demand_buyer_ind(&self) -> Vec<DemandBuyerIndJson> {
        let mut return_value: Vec<DemandBuyerIndJson> = Vec::new();

        for k_v in self.demand_acc_ind.iter() {
            let buyer = k_v.0;
            let set = k_v.1;

            let demand = DemandBuyerIndJson {
                buyer: buyer,
                demand_id: set.to_vec(),
            };

            return_value.push(demand);
        }
        return return_value;
    }
}

#[near_bindgen]
impl Contract {
    pub fn get_list_of_demands_for(&self, token_id: &TokenId) -> Vec<DemandForNftToken> {
        let mut return_value: Vec<DemandForNftToken> = Vec::new();

        if let Some(set) = self.demand_token_ind.get(&token_id) {
            for demand_id in set.iter() {
                let demand = DemandForNftToken {
                    buyer_acc: self.demand.get(&demand_id).unwrap().buyer_acc,
                    price: self.demand.get(&demand_id).unwrap().price,
                    token_id: self.demand.get(&demand_id).unwrap().token_id,
                };
                return_value.push(demand);
            }
            return return_value;
        } else {
            return vec![];
        }
    }
}



#[near_bindgen]
impl Contract {
    pub fn get_list_of_demands_for_acc(
        &self,
        account_id: &AccountId,
        start_index: Option<u64>,
        limit: Option<u64>,
    ) -> Vec<(TokenId, Balance)> {
        if let Some(demands_set) = self.demand_acc_ind.get(&account_id) {
            //let mut r_v = Vec::new();
            let start = start_index.unwrap_or(0);
            let pairs: Vec<(TokenId, Balance)> = demands_set.iter()
                //skip to the index we specified in the start variable
                .skip(start as usize)
                //take the first "limit" elements in the vector. If we didn't specify a limit, use 50
                .take(limit.unwrap_or(50) as usize)
                //we'll map the token IDs which are strings into Json Tokens
                .map(|demand_id| {
                    (self.demand.get(&demand_id).unwrap().token_id, self.demand.get(&demand_id).expect("get_list_of_demands_for_acc::no such demand").price)
                }).collect();
            return pairs;
                //since we turned the keys into an iterator, we need to turn it back into a vector to return
        } else {
            return vec![];
        }
    }
}

#[near_bindgen]
impl Contract {
    pub fn get_count_of_demands_for_acc(
        &self,
        account_id: &AccountId,
    ) -> u128 {
        if let Some(demands_set) = self.demand_acc_ind.get(&account_id) {
            return demands_set.to_vec().len() as u128;
        } else {
            return 0;
        }
    }
}

#[near_bindgen]
impl Contract {
    pub fn get_count_of_demands_for_tokens_list(
        &self,
        supremes_json: &String,
    ) -> u128 {
        let _data: ListOfTokens =
            serde_json::from_str(&supremes_json).expect("nft_on_approve::Error in msg in nft_on_transfer");
        
        let vec_of_ids = _data.list_of_tokens_id;
        
        let mut count: u128 = 0;

        for token_id in vec_of_ids.iter() {
            if let Some(set_of_demands_id) = self.demand_token_ind.get(&token_id) {
                count = count + set_of_demands_id.to_vec().len() as u128;
            }
        }

        return count;
    }
}

#[near_bindgen]
impl Contract {
    pub fn get_sum_of_bids_on_demands_for_tokens_list(
        &self,
        supremes_json: &String,
    ) -> u128 {
        let _data: ListOfTokens =
            serde_json::from_str(&supremes_json).expect("nft_on_approve::Error in msg in nft_on_transfer");
        
        let vec_of_ids = _data.list_of_tokens_id;
        
        let mut sum_bid: u128 = 0;
        for token_id in vec_of_ids.iter() {
            if let Some(set_of_demands_id) = self.demand_token_ind.get(&token_id){
                for demand_id in set_of_demands_id.iter() {
                    let price = self.demand.get(&demand_id).expect("nft_on_approve::no demands").price;
                    sum_bid = sum_bid + price;
                }
            }
        }
        return sum_bid;
    }

    pub fn get_sum_of_bids_on_demands_for_acc(
        &self,
        account_id: &AccountId,
    ) -> u128 {
        
        let mut r_v = 0;
        if let Some(demands_id_set) = self.demand_acc_ind.get(account_id) {
            for demand_id in demands_id_set.iter() {
                r_v = r_v + self.demand.get(&demand_id).expect("get_sum_of_bids_on_offers_for_acc::no offer").price;
            } 
        }
        return r_v;
    }
    
}