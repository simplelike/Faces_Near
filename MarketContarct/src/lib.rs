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
pub struct SailAnnouncement {
    pub token_id: TokenId,
    pub sailer: AccountId,
    pub price: Balance,

    pub approval_id: u64,
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
    pub stock: UnorderedMap<StockId, SailAnnouncement>,
    pub token_index: UnorderedMap<TokenId, StockId>,
    pub sailer_index: UnorderedMap<AccountId, UnorderedSet<StockId>>,

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
            sailer_index: UnorderedMap::new(StorageKey::SailerIndex.try_to_vec().unwrap()),
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

#[near_bindgen]
impl Contract {
    pub fn nft_on_approve(
        &mut self,
        token_id: &TokenId,
        owner_id: &AccountId,
        approval_id: u64,
        msg: String,
    ) -> bool {
        //Get price from master account
        let masterData: MasterData =
            serde_json::from_str(&msg).expect("Error in msg in nft_on_transfer");
        let n_p: u128 = masterData.price.parse().unwrap();
        let new_price = n_p * 1_000_000_000_000_000_000_000_000;

        match self.token_index.get(&token_id) {
            Some(stock_id) => {
                let stock_entry = self
                    .stock
                    .get(&stock_id)
                    .expect("No such entry in stock for this stock_id");
                assert_eq!(stock_entry.token_id, token_id.clone(), "Data conflict");
                assert_eq!(stock_entry.sailer, owner_id.clone(), "Error in token owner");

                let new_sail_announcement: SailAnnouncement = SailAnnouncement {
                    token_id: token_id.clone(),
                    sailer: owner_id.clone(),
                    price: new_price,

                    approval_id: approval_id,
                };
                self.stock.insert(&stock_id, &new_sail_announcement);
                //self.check_stock_index_tables_validity(stock_id, &token_id, &owner_id);
            }
            None => {
                let new_sail_announcement: SailAnnouncement = SailAnnouncement {
                    token_id: token_id.clone(),
                    sailer: owner_id.clone(),
                    price: new_price,

                    approval_id: approval_id,
                };
                //let next_stock_id = self.stock_id + 1;
                self.stock_id = self.stock_id + 1;

                self.stock.insert(&self.stock_id, &new_sail_announcement);
                self.token_index.insert(&token_id, &self.stock_id);

                if let Some(set) = self.sailer_index.get(&owner_id) {
                    let mut _new_set = set;
                    _new_set.insert(&self.stock_id);

                    self.sailer_index.remove(&owner_id);
                    self.sailer_index.insert(&owner_id, &_new_set);
                } else {
                    let mut new_set_of_stock_id: UnorderedSet<SuggestId> =
                        UnorderedSet::new(self.stock_id.try_to_vec().unwrap());
                    new_set_of_stock_id.insert(&self.stock_id);
                    self.sailer_index.insert(&owner_id, &new_set_of_stock_id);
                }
                //self.stock_id = self.stock_id + 1;
            }
        }
        return true;
    }

    
}




