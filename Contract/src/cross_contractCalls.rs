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

#[near_bindgen]
impl Contract {
    
    #[payable]
    pub fn accept_suggest_for_buying_token(&mut self, account_id: AccountId, sug_id: u128,) -> bool {
        ext_market::accept_suggest_for_buying_token(
            sug_id,
            account_id,
            1,                               //NEAR deposit we attach to the call
            env::prepaid_gas()).as_return();
        
        return true;
    }

}