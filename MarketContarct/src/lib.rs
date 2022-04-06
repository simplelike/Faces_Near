use near_sdk::borsh::{self, BorshDeserialize, BorshSerialize};
use near_sdk::collections::{UnorderedMap, UnorderedSet};
use near_sdk::json_types::U128;
use near_sdk::serde::{Deserialize, Serialize};
use near_sdk::{env, Promise, ext_contract, near_bindgen, AccountId, Balance, Gas, PromiseResult};
use std::collections::HashSet;
pub type TokenId = String;
pub type StockId = u128;
pub type SuggestId = u128;

/// Helper structure for keys of the persistent collections.
#[derive(BorshSerialize)]
pub enum StorageKey {
    Stock,
    TokenIndex,
    SailerIndex,
    Suggests,
    IdToStokeIndex,
    BuyerAccIndex,
}

mod stock_db;
mod suggests_db;


#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
pub struct MasterData {
    pub price: String,
}

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct Announcement {
    pub token_id: TokenId,
    pub sailer: AccountId,
    pub price: Balance,
    pub is_it_sale_announcement: bool

    //pub approval_id: u64,
}
#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct SuggestsForNftToken {
    pub buyer_acc: AccountId,
    pub price: Balance,

    pub id_to_stoke: u128,
}

#[near_bindgen]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct Contract {
    //Таблица продаваемых токенов
    pub stock_id: StockId,
    pub stock: UnorderedMap<StockId, Announcement>,
    pub token_index: UnorderedMap<TokenId, UnorderedSet<StockId> >,
    pub acc_index: UnorderedMap<AccountId, UnorderedSet<StockId>>,

    //Таблица предложений о покупке
    pub sugg_id: SuggestId,
    pub suggests: UnorderedMap<SuggestId, SuggestsForNftToken>,
    pub id_to_stoke_index: UnorderedMap<StockId, UnorderedSet<SuggestId>>,
    pub buyer_acc_index: UnorderedMap<AccountId, UnorderedSet<SuggestId>>,
}

impl Default for Contract {
    fn default() -> Self {
        panic!("Contract should be initialized first")
    }
}
#[near_bindgen]
impl Contract {
    #[init]
    pub fn new() -> Self {
        assert!(!env::state_exists(), "Already initialized");
        Contract {
            stock_id: 0,
            stock: UnorderedMap::new(StorageKey::Stock.try_to_vec().unwrap()),
            token_index: UnorderedMap::new(StorageKey::TokenIndex.try_to_vec().unwrap()),
            acc_index: UnorderedMap::new(StorageKey::SailerIndex.try_to_vec().unwrap()),
            sugg_id: 0,
            suggests: UnorderedMap::new(StorageKey::Suggests.try_to_vec().unwrap()),
            id_to_stoke_index: UnorderedMap::new(StorageKey::IdToStokeIndex.try_to_vec().unwrap()),
            buyer_acc_index: UnorderedMap::new(StorageKey::BuyerAccIndex.try_to_vec().unwrap()),
        }
    }
}

#[ext_contract(ext_nft)]
pub trait NFT {
    fn nft_transfer(receiver_id: AccountId, token_id: String,  approval_id: u64,);
}

fn is_promise_success() -> bool {
    assert_eq!(env::promise_results_count(),1,"Contract expected a result on the callback");
    match env::promise_result(0) {PromiseResult::Successful(_) => true, _ => false}
}

fn convert_to_yocto(price: u128) -> u128 {
    return price * 1_000_000_000_000_000_000_000_000
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
    //Первая публи
    pub fn nft_on_approve(
        &mut self,
        token_id: &TokenId,
        owner_id: &AccountId,
        approval_id: u64,
        msg: String,
    ) -> bool {
        //Get price from master account
        let masterData: MasterData =
            serde_json::from_str(&msg).expect("nft_on_approve::Error in msg in nft_on_transfer");
        let n_p: u128 = masterData.price.parse().expect("nft_on_approve::Error in price setting");
        let new_price = convert_to_yocto(n_p);


        let new_sail_announcement: Announcement = Announcement {
            token_id: token_id.clone(),
            sailer: owner_id.clone(),
            price: new_price,

            approval_id: approval_id,
        };

        add_sail_announcement_to_db(&new_sail_announcement);




        match self.token_index.get(&token_id) {
            Some(stock_id) => {
                let stock_entry = self
                    .stock
                    .get(&stock_id)
                    .expect("No such entry in stock for this stock_id");
                assert_eq!(stock_entry.token_id, token_id.clone(), "Data conflict");
                assert_eq!(stock_entry.sailer, owner_id.clone(), "Error in token owner");

                let new_sail_announcement: Announcement = Announcement {
                    token_id: token_id.clone(),
                    sailer: owner_id.clone(),
                    price: new_price,

                    approval_id: approval_id,
                };
                self.stock.insert(&stock_id, &new_sail_announcement);
                //self.check_stock_index_tables_validity(stock_id, &token_id, &owner_id);
            }
            None => {
                let new_sail_announcement: Announcement = Announcement {
                    token_id: token_id.clone(),
                    sailer: owner_id.clone(),
                    price: new_price,

                    approval_id: approval_id,
                };
                //let next_stock_id = self.stock_id + 1;
                self.stock_id = self.stock_id + 1;

                self.stock.insert(&self.stock_id, &new_sail_announcement);
                self.token_index.insert(&token_id, &self.stock_id);

                if let Some(set) = self.acc_index.get(&owner_id) {
                    let mut _new_set = set;
                    _new_set.insert(&self.stock_id);

                    self.acc_index.remove(&owner_id);
                    self.acc_index.insert(&owner_id, &_new_set);
                } else {
                    let mut new_set_of_stock_id: UnorderedSet<SuggestId> =
                        UnorderedSet::new(self.stock_id.try_to_vec().unwrap());
                    new_set_of_stock_id.insert(&self.stock_id);
                    self.acc_index.insert(&owner_id, &new_set_of_stock_id);
                }
                //self.stock_id = self.stock_id + 1;
            }
        }
        return true;
    }

    
}*/





