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
}
