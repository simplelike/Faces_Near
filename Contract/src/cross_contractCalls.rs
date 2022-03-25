use crate::*;
use near_sdk::{ext_contract, Gas};

#[ext_contract(ext_market)]
trait NonFungibleTokenApprovalsReceiver {
    //cross contract call to an external contract that is initiated during nft_approve
    fn accept_suggest_for_buying_token(
        &mut self,
        sug_id: u128
    );
}

pub const GAS_RESERVED_FOR_CURRENT_CALL: Gas = Gas(20_000_000_000_000);

#[near_bindgen]
impl Contract {
    
    #[payable]
    pub fn accept_suggest_for_buying_token(&mut self, account_id: AccountId, sug_id: u128,) -> bool {
        pub const GAS_RESERVED_FOR_CURRENT_CALL: Gas = Gas(20_000_000_000_000);
        ext_market::accept_suggest_for_buying_token(
            sug_id,
            account_id,
            1,                               //NEAR deposit we attach to the call
            env::prepaid_gas() - env::used_gas() - GAS_RESERVED_FOR_CURRENT_CALL).as_return();
        
        return true;
    }

}