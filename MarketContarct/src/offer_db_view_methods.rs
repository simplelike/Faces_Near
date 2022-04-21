use crate::*;

#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct OfferJSON {
    pub token_id: TokenId,
    pub sailer: AccountId,
    pub price: Balance,
    pub approval_id: u64,
}

#[near_bindgen]
impl Contract {
    pub fn get_list_of_offers(&self) -> Vec<OfferJSON> {
        let mut return_value: Vec<OfferJSON> = Vec::new();

        for k_v in self.offer.iter() {
            let token_id = k_v.0;
            let announcement = k_v.1;

            let newSailAnnouncement = OfferJSON {
                token_id: token_id,
                sailer: announcement.sailer,
                price: announcement.price,
                approval_id: announcement.approval_id,
            };

            return_value.push(newSailAnnouncement);
        }
        return return_value;
    }
}
#[derive(Serialize, Deserialize)]
#[serde(crate = "near_sdk::serde")]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct OfferAccIndJSON {
    pub account_id: AccountId,
    pub set: Vec<TokenId>,
}
#[near_bindgen]
impl Contract {
    pub fn get_list_of_offer_acc_ind(&self) -> Vec<OfferAccIndJSON> {
        let mut return_value: Vec<OfferAccIndJSON> = Vec::new();

        for k_v in self.offer_acc_ind.iter() {
            let account_id = k_v.0;
            let set = k_v.1.to_vec();

            let json = OfferAccIndJSON {
                account_id: account_id,
                set: set,
            };

            return_value.push(json);
        }
        return return_value;
    }
}

#[near_bindgen]
impl Contract {
    pub fn get_list_of_offers_for_acc(
        &self,
        account_id: &AccountId,
        start_index: Option<u64>,
        limit: Option<u64>,
    ) -> Vec<(TokenId, Balance)> {
        if let Some(offers_set) = self.offer_acc_ind.get(&account_id) {
            //let mut r_v = Vec::new();
            let start = start_index.unwrap_or(0);
            let pairs: Vec<(TokenId, Balance)> = offers_set.iter()
                //skip to the index we specified in the start variable
                .skip(start as usize)
                //take the first "limit" elements in the vector. If we didn't specify a limit, use 50
                .take(limit.unwrap_or(50) as usize)
                //we'll map the token IDs which are strings into Json Tokens
                .map(|token_id| {
                    (token_id.clone(), self.offer.get(&token_id).expect("get_list_of_offers_for_acc::no such offer").price)
                }).collect();

            return pairs;
                //since we turned the keys into an iterator, we need to turn it back into a vector to return
        } else {
            return vec![];
        }
    }
}

#[near_bindgen]
impl Contract {
    pub fn get_count_of_offers_for_acc(
        &self,
        account_id: &AccountId,
    ) -> u128 {
        if let Some(offers_set) = self.offer_acc_ind.get(&account_id) {
            return offers_set.to_vec().len() as u128;
        } else {
            return 0;
        }
    }

    pub fn get_offer_for_token_id(&self, token_id: &TokenId) -> Option<Announcement> {
        if let Some(offer) = self.offer.get(&token_id) {
            return Some(offer)
        }
        return None
    }

    pub fn get_sum_of_bids_on_offers_for_acc(&self, account_id: &AccountId) -> u128 {
        let mut r_v = 0;
        if let Some(tokens_id_set) = self.offer_acc_ind.get(account_id) {
            for token_id in tokens_id_set.iter() {
                r_v = r_v + self.offer.get(&token_id).expect("get_sum_of_bids_on_offers_for_acc::no offer").price;
            } 
        }
        return r_v;
    }
}