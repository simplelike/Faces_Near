use crate::*;

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct StockIndexJSON {
    pub stok_id: StockId,
    pub sug_id: Vec<SuggestId>,
}
#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct AccBuyerIndexJSON {
    pub account_id: AccountId,
    pub sug_id: Vec<SuggestId>,
}
#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct SuggestAnnouncementJSON {
    pub suggest_id: SuggestId,
    pub stock_id: StockId,
    pub buyer: AccountId,
    pub price: Balance,
}

const nftAcc: &str = "fg10.testnet";
const NO_DEPOSIT: Balance = 0;

pub const GAS_FOR_COMMON_OPERATIONS: Gas = Gas(30_000_000_000_000);
pub const GAS_RESERVED_FOR_CURRENT_CALL: Gas = Gas(20_000_000_000_000);

#[ext_contract(ext_self)]
pub trait Market {
    fn on_nft_transfer(&mut self, suggest_id: SuggestId,) -> bool;
}
#[ext_contract(ext_master)]
pub trait Master {
    fn on_transfer(succeeded:bool, price:U128, owner:AccountId);
}

#[near_bindgen]
impl Contract {
    
    #[payable]
    pub fn make_suggest_for_buying_nft( &mut self, stock_id: StockId) -> bool {

        if let Some(stock_entry) = self.stock.get(&stock_id) {

            let buyer = env::predecessor_account_id();
            let price = env::attached_deposit();
            //env::panic_str(price.to_string().as_str());
            assert_eq!(price > 0, true, "NFT is not available for sale");
            assert_eq!(price <= stock_entry.price, true, "You shouldn't set price higher than seller set");
            
            let new_suggest = SuggestsForNftToken {
                buyer_acc: buyer.clone(),
                price: price,
                id_to_stoke: stock_id,
            };
            self.sugg_id = self.sugg_id + 1;
            self.suggests.insert(&self.sugg_id, &new_suggest);

            if let Some(set) = self.id_to_stoke_index.get(&stock_id) {
                let mut n_s = set;
                self.id_to_stoke_index.remove(&stock_id);
                n_s.insert(&self.sugg_id);
                self.id_to_stoke_index.insert(&stock_id, &n_s);
            }
            else {
                let mut n_s: UnorderedSet<SuggestId> = UnorderedSet::new(
                    self.sugg_id
                        .try_to_vec()
                        .unwrap(),
                );
                n_s.insert(&self.sugg_id);
                self.id_to_stoke_index.insert(&stock_id, &n_s);
            }
            if let Some(set) = self.buyer_acc_index.get(&buyer) {
                let mut n_s = set;
                self.buyer_acc_index.remove(&buyer);
                n_s.insert(&self.sugg_id);
                self.buyer_acc_index.insert(&buyer, &n_s);
            }
            else {
                let id = self.sugg_id + 1;
                let mut n_s: UnorderedSet<SuggestId> = UnorderedSet::new(
                    id
                        .try_to_vec()
                        .unwrap(),
                );
                n_s.insert(&self.sugg_id);
                self.buyer_acc_index.insert(&buyer, &n_s); 
            }
        }
        else {
            return false;
        }
        return true;
    }

    pub fn remove_suggest_for_bying_nft(&mut self, sug_id: &SuggestId) -> bool{

        if let Some(suggest) = self.suggests.get(&sug_id) {
            let remover = env::predecessor_account_id();
            if remover != suggest.buyer_acc {
                env::panic_str("remover must be the owner of suggest ");
            }

            let stock_id = suggest.id_to_stoke;

            self.remove_sug_id_from_id_to_stoke_index(&stock_id, &sug_id);
            self.remove_sug_id_from_buyer_acc_index(&remover, &sug_id);
            
            self.suggests.remove(&sug_id);
            return true;
        }
        else {
            return false;
        }
    }

    #[payable]
    pub fn accept_suggest_for_buying_token(&mut self, sug_id: &SuggestId) -> bool {
        
        if let Some(sug) = self.suggests.get(&sug_id) {
            let accepter = env::predecessor_account_id();
            let stock_id = sug.id_to_stoke;

            if let Some(stock_entry) = self.stock.get(&stock_id) {
                assert_eq!(stock_entry.sailer, accepter, "Suggest may accept only sailer of token");
                let sold_price = sug.price;
                let buyer = sug.buyer_acc;
                let token = stock_entry.token_id;
                let approval_id = stock_entry.approval_id;

                let gas_before_call = env::used_gas();
                let nft_transfer_call =  ext_nft::nft_transfer(
                    buyer.clone(),
                    token.clone(),
                    approval_id.clone(),
                    nftAcc.parse().unwrap(),
                    env::attached_deposit(),                   
                    GAS_FOR_COMMON_OPERATIONS
                );
                let gas_before_callback = env::used_gas();
                let REMAINING_GAS: Gas = env::prepaid_gas() - env::used_gas() - GAS_FOR_COMMON_OPERATIONS - GAS_RESERVED_FOR_CURRENT_CALL;

                let self_callback = ext_self::on_nft_transfer(sug_id.clone(), env::current_account_id(), 0, REMAINING_GAS);
                let gas_after_callback = env::used_gas();
                nft_transfer_call.then(self_callback);
                /*panic!(
                    "\n{:?}\n{:?}\n{:?}\n{:?}",
                    env::prepaid_gas(),
                    gas_before_call,
                    gas_before_callback,
                    gas_after_callback
                );*/
                return true
                
            }
            else {
                env::panic_str("there is no such stock entry");
            }
        }
        else {
            env::panic_str("there is no such suggest");
        }
        return true;
    }

    pub fn on_nft_transfer(&mut self, suggest_id: SuggestId,) -> bool {
        let succeeded = is_promise_success();
        if succeeded {
            env::log_str(&"NFT transfer completed successfully");
            if let Some(suggest) = self.suggests.get(&suggest_id) {
                let stock_id = suggest.id_to_stoke;
                let buyer = suggest.buyer_acc;
                let token_id = self.stock.get(&stock_id).expect("f: on_nft_tansfer -> there is no stock entry").token_id;
                let sailer_id = self.stock.get(&stock_id).expect("f: on_nft_tansfer -> there is no stock entry").sailer;
                
                self.remove_sug_id_from_id_to_stoke_index(&stock_id, &suggest_id);
                self.remove_sug_id_from_buyer_acc_index(&buyer, &suggest_id);
                self.suggests.remove(&suggest_id);

                
                self.token_index.remove(&token_id);
                self.remove_indexes_from_sailer_index(&sailer_id, &stock_id);
                self.stock.remove(&stock_id);

                env::log_str(&"Removing complete");
            }
            else {
                env::panic_str("No such suggest_id in suggests in nft_on_transfer");
            }

        }
        return true;
    }
}

#[near_bindgen]
impl Contract {

    #[private]
    pub fn remove_sug_id_from_id_to_stoke_index(&mut self, stock_id: &StockId, sug_id: &SuggestId) -> bool {
        if let Some(set) = self.id_to_stoke_index.get(&stock_id) {
            let mut new_set = set;
            new_set.remove(&sug_id);
            self.id_to_stoke_index.remove(&stock_id);
            if !new_set.is_empty() {
                self.id_to_stoke_index.insert(&stock_id, &new_set);
            }
            return true;
        }
        else {
            return false;
        }
    }
    #[private]
    pub fn remove_sug_id_from_buyer_acc_index(&mut self, remover: &AccountId, sug_id: &SuggestId) -> bool {
        if let Some(set) = self.buyer_acc_index.get(&remover) {
            let mut new_set = set;
            new_set.remove(&sug_id);
            self.buyer_acc_index.remove(&remover);
            if !new_set.is_empty() {
                self.buyer_acc_index.insert(&remover, &new_set);
            }
            return true;
        }
        else {
            return false;
        }
    }
}

#[near_bindgen]
impl Contract {
    pub fn get_list_of_suggests(&self) -> Vec<SuggestAnnouncementJSON> {
        let mut return_value: Vec<SuggestAnnouncementJSON> = Vec::new();

        for k_v in self.suggests.iter() {
            let sug_id = k_v.0;
            let suggest_info = k_v.1;

            let newSailAnnouncement = SuggestAnnouncementJSON {
                suggest_id: sug_id,
                stock_id: suggest_info.id_to_stoke,
                buyer: suggest_info.buyer_acc,
                price: suggest_info.price,
            };

            return_value.push(newSailAnnouncement);
        }

        return return_value;
    }

    pub fn getStockIndexJSONJSON(&self) -> Vec<StockIndexJSON> {
        let mut return_value: Vec<StockIndexJSON> = Vec::new();

        for k_v in self.id_to_stoke_index.iter() {
            let stock_id = k_v.0;
            let set = k_v.1.to_vec();

            let _rv = StockIndexJSON {
                stok_id: stock_id,
                sug_id: set,
            };
            return_value.push(_rv);
        }
        return return_value;
    }

    pub fn getAccBuyerIndexJSON(&self) -> Vec<AccBuyerIndexJSON> {
        let mut return_value: Vec<AccBuyerIndexJSON> = Vec::new();

        for k_v in self.buyer_acc_index.iter() {
            let account_id = k_v.0;
            let set = k_v.1.to_vec();

            let _rv = AccBuyerIndexJSON {
                account_id: account_id,
                sug_id: set,
            };
            return_value.push(_rv);
        }
        return return_value;
    }

    pub fn clear_data(&mut self) {
        self.suggests.clear();
        self.id_to_stoke_index.clear();
        self.buyer_acc_index.clear();
    }
}