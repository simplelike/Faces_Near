use crate::*;
use near_sdk::{ext_contract, Gas};

pub const MARKET_ACCOUNT_ID: &str = "market2.vqq1.testnet";
pub const GAS_RESERVED_FOR_CURRENT_CALL: Gas = Gas(20_000_000_000_000);

#[ext_contract(ext_market)]
trait Market_Interface {
    //cross contract call to an external contract that is initiated during nft_approve
    fn accept_suggest_for_buying_token( &mut self, sug_id: u128, account_id: AccountId);

    fn make_suggest_for_buying_nft (&mut self, stock_id: u128, account_id: AccountId);
    fn remove_suggest_for_bying_nft (&self, sug_id: u128);
}


#[near_bindgen]
impl Contract {
    
    #[payable]
    pub fn make_suggest_for_buying_nft (&mut self, stock_id: u128,) {
        let CALL_GAS = env::prepaid_gas() - env::used_gas() - GAS_RESERVED_FOR_CURRENT_CALL;

        ext_market::make_suggest_for_buying_nft(
            stock_id,
            env::predecessor_account_id(),
            MARKET_ACCOUNT_ID.parse().unwrap(),
            env::attached_deposit(),
            CALL_GAS
        );
    }

    pub fn remove_suggest_for_bying_nft (self, stock_id: u128,) {
        let CALL_GAS = env::prepaid_gas() - env::used_gas() - GAS_RESERVED_FOR_CURRENT_CALL;

        ext_market::remove_suggest_for_bying_nft(
            stock_id,
            MARKET_ACCOUNT_ID.parse().unwrap(),
            0,
            CALL_GAS
        );
    }

    #[payable]
    pub fn accept_suggest_for_buying_token(&mut self, sug_id: u128,) {
        let CALL_GAS = env::prepaid_gas() - env::used_gas() - GAS_RESERVED_FOR_CURRENT_CALL;
        
        ext_market::accept_suggest_for_buying_token(
            sug_id,
            env::predecessor_account_id(),
            MARKET_ACCOUNT_ID.parse().unwrap(),
            1,                               //NEAR deposit we attach to the call
            CALL_GAS);
    }

}