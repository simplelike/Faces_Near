use crate::*;
use near_sdk::{ext_contract, Gas};

pub const MARKET_ACCOUNT_ID: &str = "market.fg6.testnet";
pub const GAS_RESERVED_FOR_CURRENT_CALL: Gas = Gas(20_000_000_000_000);

#[ext_contract(ext_market)]
trait Market_Interface {
    //cross contract call to an external contract that is initiated during nft_approve
    fn accept_suggest_for_buying_token( &mut self, sug_id: u128, account_id: AccountId);

    fn make_demand_for_buying_token (&mut self, token_id: TokenId);
    fn remove_demand_for_buying_token (&self, demand_id: u128);
}


#[near_bindgen]
impl Contract {
    
    #[payable]
    pub fn make_demand_for_buying_token (&mut self, token_id: TokenId,) {
        let CALL_GAS = env::prepaid_gas() - env::used_gas() - GAS_RESERVED_FOR_CURRENT_CALL;

        ext_market::make_demand_for_buying_token(
            token_id,
            MARKET_ACCOUNT_ID.parse().unwrap(),
            env::attached_deposit(),
            CALL_GAS
        );
    }

    pub fn remove_demand_for_buying_token (self, demand_id: u128,) {
        let CALL_GAS = env::prepaid_gas() - env::used_gas() - GAS_RESERVED_FOR_CURRENT_CALL;

        ext_market::remove_demand_for_buying_token(
            demand_id,
            MARKET_ACCOUNT_ID.parse().unwrap(),
            0,
            CALL_GAS
        );
    }
}