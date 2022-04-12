use near_sdk::borsh::{self, BorshDeserialize, BorshSerialize};
use near_sdk::collections::{UnorderedMap, UnorderedSet};
use near_sdk::json_types::U128;
use near_sdk::serde::{Deserialize, Serialize};
use near_sdk::{env, Promise, ext_contract, near_bindgen, AccountId, Balance, Gas, PromiseResult};
//use std::collections::HashSet;

pub type TokenId = String;
pub type DemandId = u128;

/// Helper structure for keys of the persistent collections.
#[derive(BorshSerialize)]
pub enum StorageKey {
    Offer,
    OfferAccInd,
    
    Demand,
    DemandTokenInd,
    DemandAccInd,

    MaxDemandBid
}
mod offer_db;
mod demand_db;

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
pub struct MasterData {
    pub price: String,
}

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct Announcement {
    pub sailer: AccountId,
    pub price: Balance,

    pub approval_id: u64,
}
#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct DemandForNftToken {
    pub buyer_acc: AccountId,
    pub price: Balance,

    pub token_id: TokenId,
}

#[near_bindgen]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct Contract {
    //Предложения о продаже
    pub offer: UnorderedMap<TokenId, Announcement>,
    pub offer_acc_ind: UnorderedMap<AccountId, UnorderedSet<TokenId>>,

    //Таблица предложений о покупке
    pub demand_id: DemandId,
    pub demand: UnorderedMap<DemandId, DemandForNftToken>,
    pub demand_token_ind: UnorderedMap<TokenId, UnorderedSet<DemandId>>,
    pub demand_acc_ind: UnorderedMap<AccountId, UnorderedSet<DemandId>>,
    
    pub max_demand_bid: UnorderedMap<TokenId, Balance>
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

            offer: UnorderedMap::new(StorageKey::Offer.try_to_vec().unwrap()),
            offer_acc_ind: UnorderedMap::new(StorageKey::OfferAccInd.try_to_vec().unwrap()),
            
            demand_id: 0,
            demand: UnorderedMap::new(StorageKey::Demand.try_to_vec().unwrap()),
            demand_token_ind: UnorderedMap::new(StorageKey::DemandTokenInd.try_to_vec().unwrap()),
            demand_acc_ind: UnorderedMap::new(StorageKey::DemandAccInd.try_to_vec().unwrap()),

            max_demand_bid: UnorderedMap::new(StorageKey::MaxDemandBid.try_to_vec().unwrap()),
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



