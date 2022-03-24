use near_sdk::borsh::{self, BorshDeserialize, BorshSerialize};
use near_sdk::{env, near_bindgen, setup_alloc};
//use near_sdk::collections::UnorderedMap;

setup_alloc!();

#[near_bindgen]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct Contract {
    pub link_to_data: String,
    pub hash_data: String
}

const LINK_TO_DATA_HASH: &str = "QmWPivV6USaqCy1rbPsNQSHo5wutr185qs4Z1GBXtZ2MmZ";
const HASH_OF_OBJECT_DATA: &str = "7a838f7029044c3e8faa2c8800a524e1a2373ea2";


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
        Contract{
            link_to_data : String::from(LINK_TO_DATA_HASH),
            hash_data: String::from(HASH_OF_OBJECT_DATA)
        }
    }
}

#[near_bindgen]
impl Contract {
    pub fn get_link_to_data(&self) -> String {
        return self.link_to_data.to_string();
    }
    pub fn get_hash_of_data(&self) -> String {
        return self.hash_data.to_string();
    }
    pub fn test_view(&self) -> String {
        return "self.hash_data".to_string();
    }
}
