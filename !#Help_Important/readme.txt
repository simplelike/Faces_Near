1. Ключи для подлкючения к pinata

API Key: 9ff76b0a8189cea07aa2
API Secret: eed51d520028f666395f5c161138bc5abd5c49ee3dac4dc75796a3b530a069e7
 JWT: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySW5mb3JtYXRpb24iOnsiaWQiOiI4YTY2ODQ3NS01Y2M2LTQ1YmItYTAwYS0wOWU1MGM3ZGZkMmYiLCJlbWFpbCI6InNpbXBsZV9saWtlQGxpc3QucnUiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwicGluX3BvbGljeSI6eyJyZWdpb25zIjpbeyJpZCI6Ik5ZQzEiLCJkZXNpcmVkUmVwbGljYXRpb25Db3VudCI6MX1dLCJ2ZXJzaW9uIjoxfSwibWZhX2VuYWJsZWQiOmZhbHNlfSwiYXV0aGVudGljYXRpb25UeXBlIjoic2NvcGVkS2V5Iiwic2NvcGVkS2V5S2V5IjoiOWZmNzZiMGE4MTg5Y2VhMDdhYTIiLCJzY29wZWRLZXlTZWNyZXQiOiJlZWQ1MWQ1MjAwMjhmNjY2Mzk1ZjVjMTYxMTM4YmM1YWJkNWM0OWVlM2RhYzRkYzc1Nzk2YTNiNTMwYTA2OWU3IiwiaWF0IjoxNjIxOTI2NTAwfQ.NCtVsLQBwGHmpxayFkttLu_wJFGAj_WSQOyp5Yv7GE4

 2. Список команд для добавления файлов в ipfs:

 	-ipfs add -w -r [DirTitle] - добавление файлов;
 	-ipfs ls -v [Hash of added file] > [fileName] - формирование файла с hash файлов, добавленных в ipfs, которые были в [DirTitle]

 	-ipfs pin remote add --service=pinata --name=[Name] [Hash]

 	(Подробнее смотри файл tutForIpfs)

3. Компиляция и деплой контракта:

	-cargo build --target wasm32-unknown-unknown --release - билд контракта

	-near dev-deploy --wasmFile target/wasm32-unknown-unknown/release/contract.wasm - деплой проекта на вновь создаваемый контракт (только в тестнет)

	-near call dev-1634889992305-95304114857363 new '{}' --accountId facesgenrator.testnet - вызов метода new у контракта пользователя dev-1634889992305-95304114857363 от имени пользователя контракта facesgenrator.testnet

	-near view dev-1634890857747-26194096813453 test_view  '{}' - вызов view метода у контркта пользователя dev-1634890857747-26194096813453
