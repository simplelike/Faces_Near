use crate::*;

#[near_bindgen]
impl Contract {
    #[payable]
    pub fn make_demand_for_buying_token(&mut self, token_id: &TokenId) {
        let buyer = env::signer_account_id();
        let price = env::attached_deposit();
        //Проверяем, что приложен депозит к вызову функции
        assert_eq!(
            price > 0,
            true,
            "make_demand_for_buying_token:: Youn need to attach deposit"
        );
        //Проверка, что предлагает цену не владелец токена
        if let Some(offer_for_token) = self.offer.get(&token_id) {
            assert_eq!(
                buyer != offer_for_token.sailer,
                true,
                "make_demand_for_buying_token::Owner couldn't buy self token"
            );
        }
        //let mut d_id: Option<DemandId> = None;
        //Проверяю, есть ли уже от buyer предложение на этот токен.
        if let Some(demand_set) = self.demand_acc_ind.get(&buyer) {
            if let Some(demand_id) = self.is_there_any_value_in(&demand_set, &token_id) {
                env::log_str("There is old demand from this buyer on this token");
                let mut demand = self
                    .demand
                    .get(&demand_id)
                    .expect("make_demand_for_buying_token:: there is no such demandId. Arch err?");
                demand.price = price;
                //Если есть - удаляем
                self.remove_demand_id_from_demand_token_id(&token_id, &demand_id);
                self.remove_demand_id_from_demand_acc_id(&buyer, &demand_id);
                self.demand.remove(&demand_id);
                //self.demand.insert(&demand_id, &demand);

                //d_id = Some(demand_id);
            }
        }
        //Создаем новое предложение
        env::log_str("New demand creating...");
        self.demand_id = self.demand_id + 1;
        let new_demand: DemandForNftToken = DemandForNftToken {
            buyer_acc: buyer.clone(),
            price: price,
            token_id: token_id.clone(),
        };

        self.demand.insert(&self.demand_id, &new_demand);
        self.update_demand_token_id(&token_id, &self.demand_id.clone());
        self.update_demand_acc_ind(&buyer, &self.demand_id.clone());

        env::log_str("New demand created...");

        //Проверяю есть ли оффер на этот токен, если да - совершаю сделку по demand_id
        if let Some(offer_for_token) = self.offer.get(&token_id) {
            env::log_str("Offer exist");
            env::log_str(price.to_string().as_str());
            env::log_str(offer_for_token.price.to_string().as_str());
            //Если прикладываемая цена выше или равна цене в оффере
            if price >= offer_for_token.price {
                env::log_str("Starting transfer");
                self.make_the_deal_for(
                    &self.demand_id.clone(),
                );
            }
        }
    }

    pub fn remove_demand_for_buying_token(&mut self, demand_id: &DemandId) {
        if let Some(demand) = self.demand.get(&demand_id) {
            let remover = env::signer_account_id();
            if remover != demand.buyer_acc {
                env::panic_str("remover must be the owner of demand ");
            }
            if self.pay_at_bet(&demand_id) {
                self.remove_demand_id_from_demand_token_id(
                    &self
                        .demand
                        .get(&demand_id)
                        .expect("remove_demand_for_buying_token::There is no such demandId")
                        .token_id,
                    &demand_id,
                );
                self.remove_demand_id_from_demand_acc_id(
                    &self
                        .demand
                        .get(&demand_id)
                        .expect("remove_demand_for_buying_token::There is no such demandId")
                        .buyer_acc,
                    &demand_id,
                );
                self.demand.remove(&demand_id);
            }
        }
    }
}

#[near_bindgen]
impl Contract {
    fn update_demand_token_id(&mut self, token_id: &TokenId, demand_id: &DemandId) {
        if let Some(set) = self.demand_token_ind.get(&token_id) {
            let mut n_s = set;
            self.demand_token_ind.remove(&token_id);
            n_s.insert(&demand_id);
            self.demand_token_ind.insert(&token_id, &n_s);
        } else {
            let mut n_s: UnorderedSet<DemandId> = UnorderedSet::new(token_id.try_to_vec().unwrap());
            n_s.insert(&demand_id);
            self.demand_token_ind.insert(&token_id, &n_s);
        }
    }
    fn update_demand_acc_ind(&mut self, buyer: &AccountId, demand_id: &DemandId) {
        if let Some(set) = self.demand_acc_ind.get(&buyer) {
            let mut n_s = set;
            self.demand_acc_ind.remove(&buyer);
            n_s.insert(&demand_id);
            self.demand_acc_ind.insert(&buyer, &n_s);
        } else {
            let mut n_s: UnorderedSet<DemandId> = UnorderedSet::new(buyer.try_to_vec().unwrap());
            n_s.insert(&demand_id);
            self.demand_acc_ind.insert(&buyer, &n_s);
        }
    }

    fn is_there_any_value_in(
        &self,
        set: &UnorderedSet<DemandId>,
        token_id: &TokenId,
    ) -> Option<DemandId> {
        for demand_id in set.iter() {
            if let Some(demand) = self.demand.get(&demand_id) {
                if demand.token_id == token_id.clone() {
                    return Some(demand_id);
                }
            }
        }
        return None;
    }

    #[private]
    pub fn remove_demand_id_from_demand_token_id(
        &mut self,
        token_id: &TokenId,
        demand_id: &DemandId,
    ) {
        if let Some(set) = self.demand_token_ind.get(&token_id) {
            let mut n_s = set;
            n_s.remove(demand_id);
            self.demand_token_ind.remove(&token_id);
            if !n_s.is_empty() {
                self.demand_token_ind.insert(&token_id, &n_s);
            }
        }
    }
    #[private]
    pub fn remove_demand_id_from_demand_acc_id(&mut self, buyer: &AccountId, demand_id: &DemandId) {
        if let Some(set) = self.demand_acc_ind.get(&buyer) {
            let mut n_s = set;
            n_s.remove(demand_id);
            self.demand_acc_ind.remove(&buyer);
            if !n_s.is_empty() {
                self.demand_acc_ind.insert(&buyer, &n_s);
            }
        }
    }
}

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct DemandJSON {
    pub demand_id: DemandId,
    pub buyer_acc: AccountId,
    pub price: Balance,

    pub token_id: TokenId,
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
                    (self.demand.get(&demand_id).unwrap().token_id, self.demand.get(&demand_id).expect("get_list_of_offers_for_acc::no such offer").price)
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