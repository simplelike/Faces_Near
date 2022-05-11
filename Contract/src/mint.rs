use crate::*;

const ROYALTY_FEE:u32 = 200;
#[near_bindgen]
impl Contract {
    
    #[payable]
    pub fn nft_mint(
        &mut self,
        token_id: TokenId,
        metadata: TokenMetadata,
        receiver_id: AccountId,
    ) {
        //measure the initial storage being used on the contract
        let initial_storage_usage = env::storage_usage();

        let mut royalty = HashMap::new();
        royalty.insert(receiver_id.clone(), ROYALTY_FEE);

        //specify the token struct that contains the owner ID 
        let token = Token {
            //set the owner ID equal to the receiver ID passed into the function
            owner_id: receiver_id,
            approved_account_ids: Default::default(),
            //the next approval ID is set to 0
            next_approval_id: 0,
            royalty: royalty
        };

        //insert the token ID and token struct and make sure that the token doesn't exist
        assert!(
            self.tokens_by_id.insert(&token_id, &token).is_none(),
            "Token already exists"
        );

        //insert the token ID and metadata
        self.token_metadata_by_id.insert(&token_id, &metadata);

        //call the internal method for adding the token to the owner
        self.internal_add_token_to_owner(&token.owner_id, &token_id);

        //calculate the required storage which was the used - initial
        let required_storage_in_bytes = env::storage_usage() - initial_storage_usage;

        //refund any excess storage if the user attached too much. Panic if they didn't attach enough to cover the required.
        refund_deposit(required_storage_in_bytes);
    }

    /*#[payable]
    pub fn nft_batch_mint(&mut self, innerdData: String)   
    {
        let initial_storage_usage = env::storage_usage();

        let elems: Vec<InnerData> = serde_json::from_str(&innerdData).unwrap(); 

        for i in 0..elems.len() {

            let elem = &elems[i];

            let _owner_id = &elem.receiver_id;
            let _token_id = &elem.token_id;
            let metadata = &elem.metadata;
            let token = Token {
                //set the owner ID equal to the receiver ID passed into the function
                owner_id: AccountId::try_from(_owner_id.clone()).unwrap(),
                approved_account_ids: Default::default(),
                //the next approval ID is set to 0
                next_approval_id: 0,
            };

            assert!(
                self.tokens_by_id.insert(&_token_id, &token).is_none(),
                "Token already exists"
            );

            //insert the token ID and metadata
            self.token_metadata_by_id.insert(&_token_id, &metadata);

        //call the internal method for adding the token to the owner
        self.internal_add_token_to_owner(&token.owner_id, &_token_id);

        //calculate the required storage which was the used - initial
        //let required_storage_in_bytes = env::storage_usage() - initial_storage_usage;

        //refund any excess storage if the user attached too much. Panic if they didn't attach enough to cover the required.
        //refund_deposit(required_storage_in_bytes);
        }

    }*/
}