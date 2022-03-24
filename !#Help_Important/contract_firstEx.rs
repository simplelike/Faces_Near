use near_sdk::borsh::{self, BorshDeserialize, BorshSerialize};
use near_sdk::collections::Vector;
use near_sdk::serde::{Deserialize, Serialize};
use near_sdk::{env, near_bindgen, setup_alloc};
use std::collections::HashMap;
//use near_sdk::collections::UnorderedMap;
use itertools::Itertools;
//use std::net::TcpStream;
//use ssh2::Session;

setup_alloc!();

//Attributes struct
#[derive(Serialize, Deserialize, BorshDeserialize, BorshSerialize)]
pub struct FaceAttr {
    title: String,
    total_count: String,
    total_precent: String,
    exponent_percent: String,
}
//generated Face aka TokenMetaData Struct
#[derive(Serialize, Deserialize, BorshDeserialize, BorshSerialize)]
pub struct TokenMetaData {
    title: String,
    generated_hash: String,
    background_attr: u64,
    eye_attr: u64,
    face_attr: u64,
    hat_attr: u64,
    jewelery_attr: u64,
    lips_attr: u64,
    mask_atrr: u64,
    shirt_attr: u64,
    small_preview_hash: String,
    mid_preview_hash: String,
    max_preview_hash: String,
    rarity: f32,
    number: u64, //https://github.com/near-apps/nft-onboarding/blob/main/contracts/src/lib.rs
}
#[derive(Serialize, Deserialize, BorshDeserialize, BorshSerialize)]
pub struct ReturnToken {
    title: String,
    generated_hash: String,
    background_attr: FaceAttr,
    eye_attr: FaceAttr,
    face_attr: FaceAttr,
    hat_attr: FaceAttr,
    jewelery_attr: FaceAttr,
    lips_attr: FaceAttr,
    mask_atrr: FaceAttr,
    shirt_attr: FaceAttr,
    small_preview_hash: String,
    mid_preview_hash: String,
    max_preview_hash: String,
    rarity: f32,
    number: u64,
}

//Main Struct
#[near_bindgen]
#[derive(BorshDeserialize, BorshSerialize)]
pub struct GeneratedFaces {
    pub attr_background: Vector<FaceAttr>,
    pub attr_eyes: Vector<FaceAttr>,
    pub attr_face: Vector<FaceAttr>,
    pub attr_hat: Vector<FaceAttr>,
    pub attr_jewelery: Vector<FaceAttr>,
    pub attr_lips: Vector<FaceAttr>,
    pub attr_mask: Vector<FaceAttr>,
    pub attr_shirt: Vector<FaceAttr>,

    pub generated_faces: Vector<TokenMetaData>,

    pub flag: u64,
    pub test_vec: Vector<i32>,
}

impl Default for GeneratedFaces {
    fn default() -> Self {
        panic!("GeneratedFaceStruct should be initialized first")
    }
}

#[near_bindgen]
impl GeneratedFaces {
    #[init]
    pub fn new() -> Self {
        assert!(!env::state_exists(), "Already initialized");
        GeneratedFaces {
            attr_background: Vector::new(b"attr_background".to_vec()),
            attr_eyes: Vector::new(b"attr_eyes".to_vec()),
            attr_face: Vector::new(b"attr_face".to_vec()),
            attr_hat: Vector::new(b"attr_hat".to_vec()),
            attr_jewelery: Vector::new(b"attr_jewelery".to_vec()),
            attr_lips: Vector::new(b"attr_lips".to_vec()),
            attr_mask: Vector::new(b"attr_mask".to_vec()),
            attr_shirt: Vector::new(b"attr_shirt".to_vec()),

            generated_faces: Vector::new(b"generated_faces".to_vec()),
            flag: 0,
            test_vec: Vector::new(b"test_vec".to_vec()),
        }

    }
}

#[near_bindgen]
impl GeneratedFaces {
    pub fn set_attr_vectors(&mut self) {
        self.clear_attr_vectors();
        let attr_vectors_data = "1;Adriatik.png;318;12.619;4;0$$$1;Al'tair.png;72;2.85714;4096;1$$$1;Avokado.png;102;4.04762;2048;2$$$1;Biryuza.png;185;7.34127;256;3$$$1;Dyushes.png;200;7.93651;64;4$$$1;Ink.png;212;8.4127;32;5$$$1;Inoplanetyanin.png;25;0.992063;16384;6$$$1;Kaberne.png;184;7.30159;128;7$$$1;Kedr.png;301;11.9444;8;8$$$1;Kentavr.png;254;10.0794;16;9$$$1;Koriandr.png;327;12.9762;2;10$$$1;Narciss.png;154;6.11111;512;11$$$1;Riv'era.png;132;5.2381;1024;12$$$1;Zombi.png;54;2.14286;8192;13$$$2;Amigasa.png;66;2.61905;3453.33;0$$$2;Bejsbolka.png;82;3.25397;58.7651;1$$$2;Beret.png;80;3.1746;281.546;2$$$2;Bob Marli.png;103;4.0873;12.2656;3$$$2;Chyotkij pacan.png;44;1.74603;1845.3;4$$$2;Clown.png;90;3.57143;385.155;5$$$2;Furazhka.png;106;4.20635;150.445;6$$$2;Hudozhnik.png;87;3.45238;720.789;7$$$2;Kamuflyazh.png;136;5.39683;1.368;8$$$2;Kardinal.png;75;2.97619;205.809;9$$$2;Kitaec.png;67;2.65873;526.892;10$$$2;Kolpak.png;115;4.56349;42.9569;11$$$2;Korona.png;20;0.793651;8840.91;12$$$2;Kotelok.png;56;2.22222;4724.16;13$$$2;Kovboj.png;107;4.24603;16.7793;14$$$2;Kufi.png;47;1.86508;986.039;15$$$2;Lysyj.png;122;4.84127;1.87142;16$$$2;Moda.png;104;4.12698;2.56011;17$$$2;Monah.png;108;4.28571;8.96608;18$$$2;Mongol.png;93;3.69048;80.3906;19$$$2;Monomah.png;21;0.833333;12094.4;20$$$2;Napoleon.png;51;2.02381;1348.9;21$$$2;Osen'.png;126;5;3.50223;22$$$2;Pank.png;110;4.36508;6.55415;23$$$2;Safari.png;99;3.92857;109.974;24$$$2;Tyubetejka.png;51;2.02381;2524.37;25$$$2;Ushanka.png;33;1.30952;6462.65;26$$$2;Vesna.png;108;4.28571;4.79105;27$$$2;Zima.png;115;4.56349;22.9541;28$$$2;Zimnij kot.png;98;3.88889;31.4013;29$$$3;Beloe na belom_1918.png;29;1.15079;2951.48;0$$$3;Belyj krest_1927.png;26;1.03175;4722.37;1$$$3;Chyornyj krug_1923.png;115;4.56349;450.36;2$$$3;Chyornyj suprematicheskij kvadrat_1915.png;23;0.912698;12089.3;3$$$3;Kompoziciya s rozovym krestom_1928_Chashnik.png;152;6.03175;42.9497;4$$$3;Kompoziciya s zhyoltoj polosoj_1920-e_Suetin.png;179;7.10317;6.5536;5$$$3;Krasnyj kvadrat_1915.png;68;2.69841;1152.92;6$$$3;Proun E1 (Gorod)_1919_Lisickij.png;160;6.34921;68.7195;7$$$3;Proun _1923_Lisickij.png;105;4.16667;109.951;8$$$3;Risunok cvetom_1917_Rozanova.png;219;8.69048;2.56;9$$$3;Suprematicheskaya kompoziciya_1916.png;29;1.15079;7555.79;10$$$3;Suprematicheskaya kompoziciya_1916_2.png;112;4.44444;175.922;11$$$3;Suprematicheskaya kompoziciya_1920-e_Chashnik.png;196;7.77778;26.8435;12$$$3;Suprematicheskaya kompoziciya_1923_Chashnik.png;207;8.21429;10.4858;13$$$3;Suprematicheskaya kompoziciya_Chashnik.png;180;7.14286;16.7772;14$$$3;Suprematicheskoe postroenie cveta_1929.png;68;2.69841;720.576;15$$$3;Suprematizm docheri.png;259;10.2778;1.6;16$$$3;Suprematizm_1915.png;93;3.69048;281.475;17$$$3;Suprematizm_1918.png;55;2.18254;1844.67;18$$$3;Zhyoltyj krest_1923_Hidekel'.png;245;9.72222;4.096;19$$$4;Bajker.png;200;7.93651;6.5536;0$$$4;Bandit.png;21;0.833333;12089.3;1$$$4;Huk.png;152;6.03175;16.7772;2$$$4;Ice cream.png;144;5.71429;109.951;3$$$4;Katana.png;54;2.14286;1844.67;4$$$4;Klingon.png;137;5.43651;68.7195;5$$$4;Kloun.png;102;4.04762;281.475;6$$$4;Memglasses.png;44;1.74603;2951.48;7$$$4;Pirat.png;52;2.06349;4722.37;8$$$4;Puaro.png;147;5.83333;26.8435;9$$$4;Richard.png;233;9.24603;2.56;10$$$4;Sailor.png;70;2.77778;720.576;11$$$4;Sparrow.png;163;6.46825;42.9497;12$$$4;Sportglasses.png;221;8.76984;4.096;13$$$4;Stalin.png;30;1.19048;7555.79;14$$$4;Sunglasses.png;204;8.09524;10.4858;15$$$4;Voin.png;87;3.45238;450.36;16$$$4;Vozhd'.png;91;3.61111;1152.92;17$$$4;Without everything.png;237;9.40476;1.6;18$$$4;Zorro.png;131;5.19841;175.922;19$$$5;Klin.png;46;1.8254;88327.8;0$$$5;Kulon.png;78;3.09524;29856.6;1$$$5;Kvadrat Malevicha.png;28;1.11111;199246;2$$$5;Without jewelry.png;2368;93.9683;1.72;3$$$6;Azure.png;683;27.1032;25;0$$$6;Brown.png;961;38.1349;5;1$$$6;Green.png;412;16.3492;125;2$$$6;Maroon.png;25;0.992063;15625;3$$$6;Violet.png;277;10.9921;625;4$$$6;Yellow.png;162;6.42857;3125;5$$$7;Ametist.png;131;5.19841;548.759;0$$$7;Atlantika.png;139;5.51587;249.436;1$$$7;Avantyurin.png;25;0.992063;12855;2$$$7;Bal'zam.png;323;12.8175;23.4256;3$$$7;Burgundiya.png;38;1.50794;5843.18;4$$$7;Defile.png;184;7.30159;51.5363;5$$$7;Dynya.png;437;17.3413;4.84;6$$$7;Kosmos.png;76;3.01587;2655.99;7$$$7;Magma.png;348;13.8095;10.648;8$$$7;Medeo.png;219;8.69048;113.38;9$$$7;Rubin.png;495;19.6429;2.2;10$$$7;Tundra.png;105;4.16667;1207.27;11$$$8;Akvamarin.png;275;10.9127;16;0$$$8;Apel'sin.png;135;5.35714;1024;1$$$8;Bazal't.png;110;4.36508;2048;2$$$8;Borneo.png;66;2.61905;4096;3$$$8;Carskij naryad.png;27;1.07143;16384;4$$$8;Indigo.png;180;7.14286;256;5$$$8;Kalipso.png;227;9.00794;64;6$$$8;Korolevskij naryad.png;47;1.86508;8192;7$$$8;Meridian.png;322;12.7778;2;8$$$8;Niva.png;141;5.59524;512;9$$$8;Paprika.png;255;10.119;8;10$$$8;Regata.png;232;9.20635;32;11$$$8;Sapfir.png;297;11.7857;4;12$$$8;Tornado.png;206;8.1746;128;13";
        let splited_attributes_from_front_end = attr_vectors_data.split("$$$");
        let vector_of_attributes: Vec<&str> = splited_attributes_from_front_end.collect();
        for attr_string in vector_of_attributes {
            let attrubute_iter = attr_string.split(";");
            let vector_of_attr_string: Vec<&str> = attrubute_iter.collect();

            let new_attribute: FaceAttr = make_face_attr(
                vector_of_attr_string[1].to_string(),
                vector_of_attr_string[2].to_string(),
                vector_of_attr_string[3].to_string(),
                vector_of_attr_string[4].to_string(),
            );

            let int_num_str: i32 = vector_of_attr_string[0].parse().unwrap();
            self.test_vec.push(&int_num_str);
            if int_num_str == 1 {
                self.attr_face.push(&new_attribute)
            } else if int_num_str == 2 {
                self.attr_hat.push(&new_attribute)
            } else if int_num_str == 3 {
                self.attr_background.push(&new_attribute)
            } else if int_num_str == 4 {
                self.attr_mask.push(&new_attribute)
            } else if int_num_str == 5 {
                self.attr_jewelery.push(&new_attribute)
            } else if int_num_str == 6 {
                self.attr_eyes.push(&new_attribute)
            } else if int_num_str == 7 {
                self.attr_lips.push(&new_attribute)
            } else if int_num_str == 8 {
                self.attr_shirt.push(&new_attribute)
            }
        }
        let generated_faces_data = concat!(
            "3.png;8;14;6;16;3;0;3;13;8.77311\n",
            "7.png;0;24;12;17;3;0;8;11;7.66935\n",
            "1.png;5;10;12;13;3;0;8;11;3.99453\n",
            "6.png;3;10;7;10;3;2;7;0;17.6628\n",
            "5.png;9;9;16;12;3;1;8;6;2.67988\n",
            "4.png;10;5;16;3;2;1;10;8;279.613\n",
            "9.png;0;11;2;13;1;0;6;8;119.423\n",
            "10.png;9;28;2;9;3;1;10;0;3.80992\n",
            "8.png;10;14;13;11;0;1;5;2;216.156\n",
            "12.png;7;6;5;3;3;1;0;0;7.02731\n",
            "11.png;11;23;11;10;3;1;10;8;5.48014\n",
            "13.png;10;2;17;15;3;1;9;10;4.32749\n",
            "15.png;4;19;7;19;3;1;8;6;4.03912\n",
            "14.png;4;1;17;4;3;1;11;12;13.9007\n",
            "16.png;9;21;16;12;3;2;6;13;8.15125\n",
            "18.png;7;11;13;0;3;2;5;8;4.84489\n",
            "17.png;4;3;15;16;3;0;10;11;6.5429\n",
            "19.png;9;11;12;10;3;0;10;1;8.69736\n",
            "20.png;12;15;13;5;3;1;10;12;10.1315\n",
            "21.png;0;24;19;0;3;1;10;0;1.43059\n",
            "23.png;2;8;14;18;3;4;11;2;36.7918\n",
            "24.png;9;13;19;9;3;2;8;13;17.8181\n",
            "22.png;9;22;5;5;3;4;4;8;20.5841\n",
            "25.png;8;14;11;19;3;1;4;8;13.8152\n",
            "26.png;7;3;16;12;3;1;5;10;2.61067\n",
            "27.png;11;3;12;3;3;0;9;8;7.33273\n",
            "28.png;8;29;11;18;3;1;0;8;5.30641\n",
            "29.png;8;20;14;13;3;4;5;2;33.347\n",
            "31.png;5;14;5;18;3;0;10;10;1.70678\n",
            "30.png;12;12;18;18;3;1;9;0;22.4172\n",
            "32.png;10;5;7;16;3;0;10;6;6.06445\n",
            "33.png;9;0;18;0;3;4;3;1;32.6268\n",
            "34.png;5;5;11;14;3;0;8;1;22.367\n",
            "35.png;3;22;18;16;3;4;6;0;18.46\n",
            "37.png;9;16;6;0;3;4;3;12;13.3906\n",
            "36.png;10;2;7;9;3;1;9;6;4.28348\n",
            "38.png;5;27;19;13;3;0;10;9;5.14083\n",
            "39.png;9;10;16;15;3;1;5;5;5.27579\n",
      );
        self.set_generated_faces_vector(generated_faces_data.to_string());
    }



    fn set_generated_faces_vector(&mut self, input_data: String) {
        self.clear_generated_faces_vector();
        
        let splited_attributes_from_front_end = input_data.split("\n");
        let vector_of_faces: Vec<&str> = splited_attributes_from_front_end.collect(); // 2520 faces here

        let mut temp: HashMap<u64, TokenMetaData> = HashMap::new();
        for face in vector_of_faces {
            let attrubute_iter = face.split(";");
            let vector_of_attr_string: Vec<&str> = attrubute_iter.collect();

            let iter = vector_of_attr_string[0].split(".");
            let v: Vec<&str> = iter.collect();
            let index: u64 = v[0].parse().unwrap();
            let new_face: TokenMetaData = make_token(
                vector_of_attr_string[0].to_string(),
                vector_of_attr_string[1].parse().unwrap(),
                vector_of_attr_string[2].parse().unwrap(),
                vector_of_attr_string[3].parse().unwrap(),
                vector_of_attr_string[4].parse().unwrap(),
                vector_of_attr_string[5].parse().unwrap(),
                vector_of_attr_string[6].parse().unwrap(),
                vector_of_attr_string[7].parse().unwrap(),
                vector_of_attr_string[8].parse().unwrap(),
                vector_of_attr_string[9].parse().unwrap(),
                String::from(""),
                String::from(""),
                String::from(""),
                String::from(""),
                index,
            );
            temp.insert(index, new_face);
        }

        for i in temp.keys().sorted() {
            let value = temp.get(&i).unwrap();
            self.generated_faces.push(&value);
        }
    }

    pub fn get_generated_faces_vector(&self, from: usize, to:usize) -> Vec<ReturnToken> {
        let mut return_value: Vec<ReturnToken> = Vec::new();
        //let slice = self.generated_faces.to_vec()[from..to];
        let mut _to = to;
        if _to > self.generated_faces.to_vec().len() - 1 {
            _to = self.generated_faces.to_vec().len() - 1;
        }
        for t_md in self.generated_faces.to_vec()[from..=_to].iter() {
            let r_t = make_return_token(
                t_md.title.to_string(),
                self.attr_face
                    .get(t_md.face_attr)
                    .expect("No such attr_face face element"),
                self.attr_hat
                    .get(t_md.hat_attr)
                    .expect("No such hat_attr face element"),
                self.attr_background
                    .get(t_md.background_attr)
                    .expect("No such attr_background face element"),
                self.attr_mask
                    .get(t_md.mask_atrr)
                    .expect("No such attr_mask face element"),
                self.attr_jewelery
                    .get(t_md.jewelery_attr)
                    .expect("No such attr_jewelery face element"),
                self.attr_eyes
                    .get(t_md.eye_attr)
                    .expect("No such attr_eyes face element"),
                self.attr_lips
                    .get(t_md.lips_attr)
                    .expect("No such attr_lips face element"),
                self.attr_shirt
                    .get(t_md.shirt_attr)
                    .expect("No such attr_shirt face element"),
                t_md.rarity,
                t_md.generated_hash.to_string(),
                t_md.small_preview_hash.to_string(),
                t_md.mid_preview_hash.to_string(),
                t_md.max_preview_hash.to_string(),
                t_md.number,
            );
            return_value.push(r_t);
        }
        return return_value;
    }

    pub fn get_flag(&self) -> u64 {
        return self.flag;
    }

    pub fn test_to_ssh2(&self) {

        /*let tcp = TcpStream::connect("criptbook.myjino.ru").unwrap();
        let mut sess = Session::new().unwrap();
        sess.set_tcp_stream(tcp);
        sess.handshake().unwrap();

        sess.userauth_password("criptbook", "R16jln*Top").unwrap();
        assert!(sess.authenticated());
*/
    }

    fn clear_attr_vectors(&mut self) {
        self.attr_background.clear();
        self.attr_eyes.clear();
        self.attr_face.clear();
        self.attr_hat.clear();
        self.attr_jewelery.clear();
        self.attr_lips.clear();
        self.attr_mask.clear();
        self.attr_shirt.clear();

    }
    fn clear_generated_faces_vector(&mut self) {
        self.generated_faces.clear();
    }
}

fn make_face_attr(
    title: String,
    total_count: String,
    total_precent: String,
    exponent_percent: String,
) -> FaceAttr {
    FaceAttr {
        title,
        total_count,
        total_precent,
        exponent_percent,
    }
}

fn make_token(
    title: String,
    face_attr: u64,
    hat_attr: u64,
    background_attr: u64,
    mask_atrr: u64,
    jewelery_attr: u64,
    eye_attr: u64,
    lips_attr: u64,
    shirt_attr: u64,
    rarity: f32,
    generated_hash: String,
    small_preview_hash: String,
    mid_preview_hash: String,
    max_preview_hash: String,
    number: u64,
) -> TokenMetaData {
    TokenMetaData {
        title,
        generated_hash,
        background_attr,
        eye_attr,
        face_attr,
        hat_attr,
        jewelery_attr,
        lips_attr,
        mask_atrr,
        shirt_attr,
        small_preview_hash,
        mid_preview_hash,
        max_preview_hash,
        rarity,
        number,
    }
}

fn make_return_token(
    title: String,
    face_attr: FaceAttr,
    hat_attr: FaceAttr,
    background_attr: FaceAttr,
    mask_atrr: FaceAttr,
    jewelery_attr: FaceAttr,
    eye_attr: FaceAttr,
    lips_attr: FaceAttr,
    shirt_attr: FaceAttr,
    rarity: f32,
    generated_hash: String,
    small_preview_hash: String,
    mid_preview_hash: String,
    max_preview_hash: String,
    number: u64,
) -> ReturnToken {
    ReturnToken {
        title,
        generated_hash,
        background_attr,
        eye_attr,
        face_attr,
        hat_attr,
        jewelery_attr,
        lips_attr,
        mask_atrr,
        shirt_attr,
        small_preview_hash,
        mid_preview_hash,
        max_preview_hash,
        rarity,
        number,
    }
}
