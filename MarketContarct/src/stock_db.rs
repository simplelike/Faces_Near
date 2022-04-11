use crate::*;

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct SailAnnouncementJSON {
    pub stock_id: StockId,
    pub token_id: TokenId,
    pub sailer: AccountId,
    pub price: Balance,

    pub approval_id: u64,
}

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct TokenIndexJSON {
    pub token_id: TokenId,
    pub stock_id: StockId,
}
#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct AccIndexJSON {
    pub account_id: AccountId,
    pub stock_id: Vec<StockId>,
}


#[near_bindgen]
impl Contract {
   
    pub fn set_the_price_of_the_token(&mut self, token_id: &TokenId, price: &String) {
        
        let seller = env::signer_account_id();
        let n_p: u128 = price.parse().expect("set_the_price_of_the_token::Error in price setting");
        let new_price = convert_to_yocto(n_p);

        let new_announcement = Announcement {
            token_id: token_id.clone(),
            sailer: seller.clone(),
            price: new_price,
            //Флажок, что объявление о продаже
            is_it_sale_announcement: true
        };

        //Меняем цену
        if let Some(stock_id) = self.find_stock_id_for_sale_of(&token_id, &seller) {
           self.stock.insert(&stock_id, &new_announcement);
        }
        else { //Устанавливаем впервые
            self.stock_id = self.stock_id + 1;
            self.stock.insert(&self.stock_id, &new_announcement);
            self.add_stock_id_to_token_index(&token_id, &self.stock_id);
            self.add_stock_id_to_acc_index(&seller, &self.stock_id);
        }
    }


    pub fn delete_token_sale_announcement(&mut self, stock_id: &StockId) {
        
    }

    #[private]
    fn find_stock_id_for_sale_of(self, token_id: &TokenId, seller: &AccountId) -> Option<StockId> {
        if let Some(set_of_stock_id) = self.acc_index.get(&seller) {
            for stock_id in set_of_stock_id.iter() {
                if let Some(stock_entry) = self.stock.get(&stock_id) {
                    assert_eq!(seller, stock_entry.sailer, "find_stock_id_for_sale_of::sailers are different");
                    assert_eq!(token_id, stock_entry.token_id, "find_stock_id_for_sale_of::token_ids are different");
                    if stock_entry.is_it_sale_announcement {
                        Some(stock_id);
                    }
                }
            }
        }
        None
    }
    #[private]
    fn add_stock_id_to_token_index(self, token_id: &TokenId, stock_id: &StockId) {
        if let Some(set) = self.token_index.get(&token_id) {
            let mut _new_set = set;
            _new_set.insert(&self.stock_id);

            self.token_index.remove(&token_id);
            self.token_index.insert(&token_id, &_new_set);
        } else {
            let mut new_set_of_stock_id: UnorderedSet<SuggestId> =
                UnorderedSet::new(self.token_index.try_to_vec().unwrap());
            new_set_of_stock_id.insert(&self.stock_id);
            self.token_index.insert(&token_id, &new_set_of_stock_id);
        }
    }
    #[private]
    fn add_stock_id_to_acc_index(self, seller: &AccountId, stock_id: &StockId) {

        if let Some(set) = self.acc_index.get(&seller) {
            let mut _new_set = set;
            _new_set.insert(&self.stock_id);

            self.acc_index.remove(&seller);
            self.acc_index.insert(&seller, &_new_set);
        } else {
            let mut new_set_of_stock_id: UnorderedSet<SuggestId> =
                UnorderedSet::new(self.stock_id.try_to_vec().unwrap());
            new_set_of_stock_id.insert(&self.stock_id);
            self.acc_index.insert(&seller, &new_set_of_stock_id);
        }
    }

}



/*#[near_bindgen]
impl Contract {
    pub fn delete_token_sale_announcement(&mut self, stock_id: &StockId) -> bool {
        let sender_id = env::predecessor_account_id();
        if let Some(stock_entry) = self.stock.get(&stock_id) {
            let owner_id = stock_entry.sailer;
            let token_id = stock_entry.token_id;

            if sender_id != owner_id {
                env::panic_str("predecessor must be the owner of token ");
            }

            self.token_index.remove(&token_id);
            self.remove_indexes_from_sailer_index(&owner_id, &stock_id);

            if let Some(set_of_suggests_ids_for_this_stock_id) = self.id_to_stoke_index.get(&stock_id) {
                for sug_id in set_of_suggests_ids_for_this_stock_id.iter() {
                    if let Some(suggest_entry) = self.suggests.get(&sug_id) {
                        assert_eq!(stock_id, &suggest_entry.id_to_stoke, "Error");
                        if let Some(set) = self.buyer_acc_index.get(&suggest_entry.buyer_acc) {
                            let mut new_set = set;
                            new_set.remove(&sug_id);

                            self.buyer_acc_index.remove(&suggest_entry.buyer_acc);
                            if !new_set.is_empty() {
                                self.buyer_acc_index
                                    .insert(&suggest_entry.buyer_acc, &new_set);
                            }
                        }
                        self.suggests.remove(&sug_id);
                    }
                }
                self.id_to_stoke_index.remove(&stock_id);
            }
            self.stock.remove(&stock_id);

            return true;
        } else {
            return false;
        }
    }

    #[private]
    pub fn remove_indexes_from_sailer_index(&mut self, owner_id: &AccountId, stock_id: &StockId) {
        if let Some(set_of_acc_ind) = self.sailer_index.get(&owner_id) {
            let mut new_set = set_of_acc_ind;
            new_set.remove(&stock_id);
            self.sailer_index.remove(&owner_id);
            if !new_set.is_empty() {
                self.sailer_index.insert(&owner_id, &new_set);
            }
        } else {
            env::panic_str(
                "f: remove_indexes_from_sailer_index -> There is no set in sailer_index",
            );
        }
    }
}

#[near_bindgen]
impl Contract {
    pub fn getSellerPriceForTokenId(&self, token_id: &TokenId) -> Option<Balance> {
        match self.token_index.get(&token_id) {
            Some(stock_id) => match self.stock.get(&stock_id) {
                Some(stock_entry) => {
                    assert_eq!(
                        stock_entry.token_id,
                        String::from(token_id),
                        "Data conflict"
                    );
                    return Some(stock_entry.price);
                }
                None => None,
            },
            None => None,
        }
    }

    pub fn getListOfSailedTokens(&self) -> Vec<SailAnnouncementJSON> {
        let mut return_value: Vec<SailAnnouncementJSON> = Vec::new();

        for k_v in self.token_index.iter() {
            let token_id = k_v.0;
            let stock_id = k_v.1;
            if let Some(stock_entry) = self.stock.get(&stock_id) {
                //assert_eq!(token_id, stock_entry.token_id, "Error");

                let newSailAnnouncement = SailAnnouncementJSON {
                    stock_id: stock_id,
                    token_id: stock_entry.token_id,
                    sailer: stock_entry.sailer,
                    price: stock_entry.price,
                    approval_id: stock_entry.approval_id,
                };
                return_value.push(newSailAnnouncement);
            }
        }

        return return_value;
    }

    pub fn getTokenIndexJson(&self) -> Vec<TokenIndexJSON> {
        let mut return_value: Vec<TokenIndexJSON> = Vec::new();

        for k_v in self.token_index.iter() {
            let token_id = k_v.0;
            let stock_id = k_v.1;

            let _rv = TokenIndexJSON {
                token_id: token_id,
                stock_id: stock_id,
            };
            return_value.push(_rv);
        }
        return return_value;
    }

    pub fn getAccIndexJson(&self) -> Vec<AccIndexJSON> {
        let mut return_value: Vec<AccIndexJSON> = Vec::new();

        for k_v in self.sailer_index.iter() {
            let account_id = k_v.0;
            let set = k_v.1.to_vec();

            let _rv = AccIndexJSON {
                account_id: account_id,
                stock_id: set,
            };
            return_value.push(_rv);
        }
        return return_value;
    }
}*/
