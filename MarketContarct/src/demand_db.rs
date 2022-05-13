use crate::*;

#[near_bindgen]
impl Contract {
    #[payable]
    pub fn make_demand_for_buying_token(&mut self, token_id: &TokenId) {
        let buyer = env::signer_account_id();
        let price = env::attached_deposit();

        //measure the initial storage being used on the contract
        let initial_storage_usage = env::storage_usage();

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
                let demand = self
                    .demand
                    .get(&demand_id)
                    .expect("make_demand_for_buying_token:: there is no such demandId. Arch err?");
                //demand.price = price;
                //Если есть - удаляем
                //Но сначала возвращаем старую ставку
                if self.pay_at_bet(&buyer, demand.price) {
                    env::log_str("Old demand returned");
                    self.remove_demand_id_from_demand_token_id(&token_id, &demand_id);
                    self.remove_demand_id_from_demand_acc_id(&buyer, &demand_id);
                    self.demand.remove(&demand_id);
                }
                
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
            demand_id: self.demand_id.clone()
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

         //calculate the required storage which was the used - initial
         if env::storage_usage() > initial_storage_usage {
            let required_storage_in_bytes = env::storage_usage() - initial_storage_usage;

            //refund any excess storage if the user attached too much. Panic if they didn't attach enough to cover the required.
            refund_deposit(required_storage_in_bytes, Some(env::signer_account_id()));
        }
    }

    pub fn remove_demand_for_buying_token(&mut self, demand_id: &DemandId) {
        if let Some(demand) = self.demand.get(&demand_id) {
            let remover = env::signer_account_id();
            if remover != demand.buyer_acc {
                env::panic_str("remover must be the owner of demand ");
            }
            if self.pay_at_bet(&remover, demand.price) {
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
            let id = demand_id.to_string() + token_id;
            let mut n_s: UnorderedSet<DemandId> = UnorderedSet::new(id.try_to_vec().unwrap());
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
            let id = demand_id.to_string() + buyer.as_str();
            let mut n_s: UnorderedSet<DemandId> = UnorderedSet::new(id.try_to_vec().unwrap());
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