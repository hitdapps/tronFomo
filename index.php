<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">


<style type="text/css">

	{
		margin: 0px;
		color:#f1f4fb;
	}

	body{
		background-color: #10181B;
		color:#f1f4fb;
	}

	.container{
		width:100%;
		height: 100%;
		background-color: #10181b;
	}

	.fomo-wrapper{
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100%;
	}

	.fomo-inner-wrapper{
		background-color: rgba(44,26,36,.8);
		display: flex;
		justify-content: center;
		align-items: center;
		height: auto;
		padding: 15px;
		position: relative;
	}

	.fomo-box{
		height: auto;
		width: 400px;
		flex-grow: 0;
		flex-shrink: 0;
		padding: 15px;
		margin-right: 20px;
		text-align: center;
	}
	
	.fomo-pot{
		flex-grow: 1;
		height: 300px;
		padding: 15px;
		height: auto;
		text-align: center;
	}

	.single-section{
		padding-bottom: 10px;

	}
	
	.single-section h3{
		margin: 0px;
		margin-bottom: 15px;
		color:#df9522;
		font-size: 25px;
	}

	.single-section h5{
		font-size: 18px;
		color:#f1f4fb;
	}

	.button-section{
		margin-top: 20px;
		display: flex;
		justify-content: space-around;
	}

	.user-info-wrapper{
		position: absolute;
		top: 0px;
		right: 0px;
		width: auto;
		height: auto;
		border: 3px solid rgba(51,26,39,1);
		top:-200px;
		right: 0px;
		padding: 15px;
		background-color: rgba(44,26,36,.8);
	}

	.user-sub-wrapper{
		padding: 5px 0px;
	}

	.user-sub-wrapper>h5{
		font-size: 18px;
		color:#df9522;
	}

	input{
		color: white;
		background-color: rgba(22,33,38,1);
		text-align: center;
		width: 100px;
		height: 50px;
		margin-right: 5px;
	}

	#round-timer{
		height: 50px;
	}

	.user-vault-wrapper{
		display: flex;
		justify-content: space-around;
		margin-top: 35px;
	}

	.modal-content{
		background-color: #202022;
		color:white;
	}

</style>

<!DOCTYPE html>
<html>
	<head>
		<title>Tron Fomo</title>
	</head>
	<body>
		<div class="container">
			<div class="fomo-wrapper">
				
				<div class="fomo-inner-wrapper">
					<div class="user-info-wrapper">
						<div class="user-sub-wrapper">
							<h5>Address</h5>
							<span id="user_address"></span>
						</div>
						<div class="user-sub-wrapper">
							<h5>Balance</h5>
							<h6><span id="user-balance"></span> TRX</h6>
						</div>
					</div>
					<div class="fomo-box">
						<div class="single-section">
							<h3>Buy Keys</h3>
							<input type="text" id="selected-key-field" value="0" />
							<button class="btn btn-success" id="buy-key-btn">Purchase</button>
						</div>
						<div class="button-section">
							<button class="btn btn-success" id="withdraw-btn">Withdraw</button>
						</div>
						<div class="user-vault-wrapper">
							<div class="single-section">
								<h3>Vault Balance</h3>
								<input type="text" id="vault-balance" value="0" />
							</div>
							<div class="single-section">
								<h3>Owned Keys</h3>
								<input type="text" id="owned-keys" value="0" />
							</div>
						</div>
						
					</div>
					<div class="fomo-pot">
						<div class="single-section">
							<h3>Pot Value</h3>
							<h5 id="pot-value"></h5>
						</div>
						<div class="single-section">
							<h3>Current Leader</h3>
							<h5 id="current-leader"></h5>
						</div>
						<div class="single-section">
							<h3>Total Keys</h3>
							<h5 id="total-keys"></h5>
						</div>
						<div class="single-section">
							<h3>Round <span id="current-round-no"></span> Ends in</h3>
							<h5 id="round-timer"></h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>


<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/bootbox.min.js"></script>
<script src="assets/js/bignumber.min.js"></script> 
<script src="assets/js/countdown.min.js"></script> 
<script src="assets/js/TronWeb.js"></script> 

<script type="text/javascript">

	const contractAddress = "41dc24f51f6c8667b7e3b417e2f78da3662e80b2a9";
	var user_address;

	 		setTimeout(function(){
				 switchTronLinkAccount();
			},3000);

	//login user if has tronlink installed in the browser
	 async function switchTronLinkAccount(){

	 	//console.log(window.tronWeb);

	 	if(window.tronWeb){

	 		var tronweb = window.tronWeb;

	 		var hexAddress = tronweb.defaultAddress.hex;
	        var base58Address = tronweb.defaultAddress.base58;

	        if(hexAddress && base58Address && user_address!=base58Address) {
	        	user_address = base58Address;

	        	var balance = await tronweb.trx.getBalance(base58Address);
	        	var trxbalance = tronweb.fromSun(balance);

	        	console.log("base58Address "+base58Address);

	        	//bootbox.alert("Hello world");
	        	$("#user_address").html(base58Address);
	        	$("#user-balance").html(trxbalance);

	        	let contract = await tronweb.contract().at(contractAddress);

	        	//get fomo information
	        	let userData = await contract.players(base58Address).call();
		        userData.keysOwned = new BigNumber(userData.keysOwned).toNumber();
		        userData.vault = new BigNumber(userData.vault).dividedBy(100*1000000).toFixed(6);

		        console.log(userData);
		       	
		       	$("#vault-balance").val(userData.vault+" TRX");
		       	$("#owned-keys").val(userData.keysOwned);

	        }else{
	        	//bootbox.alert("Please login onto your tronlink wallet");
	        	//console.log("Please login onto your tronlink wallet");
	        }
	 	}else{
	 		//bootbox.alert("Please install tronlink chrome extension to interact");
	 		//console.log("Please install tronlink chrome extension to interact");
	 	}

	 		setTimeout(function(){
				 switchTronLinkAccount();
			},3000);
       
    }


    const tronweb = initiateTronWeb();

    initiatiateContract();

    var countdown;

    async function initiatiateContract(){

    	let contract = await tronweb.contract().at(contractAddress);
		//console.log(contract);

		synchFomoContractRoundData();

		
	    async function synchFomoContractRoundData(){

	    
			let currentRound = await contract.currentRound().call();
		//	console.log(currentRound);

			currentRound.roundNo = new BigNumber(currentRound.roundNo).toNumber();
			currentRound.jackPot = new BigNumber(currentRound.jackPot).dividedBy(100*1000000).toFixed(6);
			currentRound.nextRoundPot = new BigNumber(currentRound.nextRoundPot).dividedBy(100*1000000).toFixed(6);
			currentRound.starttime = new BigNumber(currentRound.starttime).toNumber();
			currentRound.endtime = new BigNumber(currentRound.endtime).toNumber();
			currentRound.currentLeader = tronweb.address.fromHex(currentRound.currentLeader);
			currentRound.keysSold = new BigNumber(currentRound.keysSold).toNumber();
			currentRound.dividentPot = new BigNumber(currentRound.dividentPot).dividedBy(100*1000000).toFixed(6);
			currentRound.distributedDivident = new BigNumber(currentRound.distributedDivident).dividedBy(100*1000000).toFixed(6);

			//const contractBalance = await tronweb.trx.getBalance(demoConfig.tron.contractAddress);

			//console.log(contractBalance);

			$("#pot-value").text(currentRound.jackPot + " TRX");
			$("#current-leader").text(currentRound.currentLeader);
			$("#current-round-no").text(currentRound.roundNo);
			$("#total-keys").text(currentRound.keysSold);

			let currentTime = Date.now()/1000;

			//console.log("Current Time "+currentTime);
			//console.log("Round End Time "+currentRound.endtime);

			//currentRound.endtime=  currentTime+3620;

			if(currentRound.endtime>=currentTime && currentRound.isRunning) {

				$(".round-ended-text").hide();
				$("#start-round-btn").prop("disabled",true);
				var timeRemains = currentRound.endtime - currentTime;
			/*	var clock = $('#round-timer').FlipClock(timeRemains, {
					countdown: true
				});*/
				
			//	countdown.dateEnd = new Date(currentRound.endtime*1000);

				/*countdown = null;

				countdown = new Countdown({
					    selector: '#round-timer',
					    msgBefore: "Fetching Timer",
					    msgAfter: "Round Ended",
					    msgPattern: "{hours}:{minutes}:{seconds}",
					   // dateStart: new Date(currentRound.starttime),
					    dateEnd: new Date(currentRound.endtime*1000)
					});;*/


				  $("#round-timer").countdown({
				    date: new Date(currentRound.endtime*1000),
				    text:"%s:%s:%s:%s"
				});

				
			}else{
				$("#round-timer").text("Round Ended");
			}

			$(".info-loader").hide();

			setTimeout(function () {
				synchFomoContractRoundData();

			},5000);
		}


		$("#buy-key-btn").click(function(){
			
			if(user_address){
				var selectedKeys = $("#selected-key-field").val();
				var el = $(this);

				bootbox.confirm("It may cost 600 TRX as gas fee. Do you want to proceed?", 
				function(result){ 
					if(result){
						if(!isNaN(selectedKeys) && selectedKeys>0){
							buyKeys(selectedKeys,el);
						}else{
							bootbox.alert("Please select vaild keys");
						}
					}
				})
			}else{
				if(window.tronweb){
					if(!window.hexAddress && !window.base58Address){
						bootbox.alert("Please login onto your tronlink wallet");
					}
				}else{
					bootbox.alert("Please install tronlink to interact with our dapp");
				}
			}
			
		});

		//buy keys function
		async function buyKeys(keyCount,el){

			try{
				el.html('<i class="fa fa-circle-o-notch fa-spin"></i> Processing');

				var tronweb = window.tronWeb;

				let contract = await tronweb.contract().at(contractAddress);

				let currentkeyPrice = await contract.ROUND_KEY_CURRENT_PRICE().call();
	           currentkeyPrice = new BigNumber(currentkeyPrice).toNumber();

	            var keyPrice = keyCount*currentkeyPrice;

	            let txID;
	            let reffer;

	            if (reffer) {
	                txID = await
	                contract.buyKeysReffered(keyCount, reffer).send({
	                    callValue: keyPrice,
	                    shouldPollResponse: false
	                });
	            } else {
	                txID = await
	                contract.buyKeys(keyCount).send({
	                    callValue: keyPrice,
	                    shouldPollResponse: false,
	                    
	                });
	            }


	            var requestSent = false;


	            if(txID!=""){

	                console.log("KeyPurchase txid " + txID);

	                setTimeout( () => {

	                    contract["PaymentSuccess"]().watch(function(err, res) {
	                        if(!err){
	                            if(res.transaction==txID && !requestSent){
	                                console.log(res);
	                                requestSent = true;
	                                el.html('Purchase');
	                                updateBalance(user_address);
	                                bootbox.alert("Congratulation. you have become the new leader");
	                            }
	                        }else{
	                            console.log(err);
	                        }

	                    });
	                },2000);


	                var txInfo;

	                while (!txInfo || !txInfo.id) {
	                    txInfo = await tronweb.trx.getTransactionInfo(txID);
	                }

	                if(txInfo &&  txInfo.receipt.result != 'SUCCESS'){
	                    var errorMsg = tronweb.toUtf8(txInfo.contractResult[0]);
	                    if(errorMsg==""){
	                        errorMsg = tronweb.toUtf8(txInfo.resMessage);
	                    }
	                    throw errorMsg;
	                }else{
	                    if(!requestSent){
	                        requestSent = true;
	                        console.log("Got Tx Data");
	                      	console.log(txInfo);
	                        el.html('Purchase');
	                         updateBalance(user_address);
	                        bootbox.alert("Congratulation. you have become the new leader");
	                    }
	                }
	            }



	        }catch(err){
	        	console.log(err);
	        	  el.html('Purchase');
			}


			
		}



		$("#withdraw-btn").click(function(){
			var el = $(this);
			if(user_address){
				withdraw_balance(el);
			}else{
				if(window.tronWeb){
					if(!window.hexAddress && !window.base58Address){
						bootbox.alert("Please login onto your tronlink wallet");
					}
				}else{
					bootbox.alert("Please install tronlink to interact with our dapp");
				}
			}
		
		});

		async function withdraw_balance(el){

			el.html('<i class="fa fa-circle-o-notch fa-spin"></i> Processing');

			try {

				var tronweb = window.tronWeb;
				let contract = await tronweb.contract().at(contractAddress);

		        let txID;

		        txID = await
		            contract.withdraw(user_address).send({
		                callValue: 0,
		                shouldPollResponse: false
		            });


		        var requestSent = false;

		        if(txID!=""){

		            console.log("Withdrawl txid " + txID);

		            setTimeout( () => {

		                contract["PaymentSuccess"]().watch(function(err, res) {
		                    if(!err){
		                        if(res.transaction==txID && !requestSent){
		                            console.log(res);
		                            requestSent = true;
		                              updateBalance(user_address);
		                            bootbox.alert("You have successfully withdrawn your vault balance");
		                        }
		                    }else{
		                        console.log(err);
		                    }

		                });
		            },2000);


		            var txInfo;

		            while (!txInfo || !txInfo.id) {
		                 txInfo = await tronweb.trx.getTransactionInfo(txID);
		            }

		            if(txInfo &&  txInfo.receipt.result != 'SUCCESS'){

		                var errorMsg = TronWeb.toUtf8(txInfo.contractResult[0]);
		                if(errorMsg==""){
		                    errorMsg = TronWeb.toUtf8(txInfo.resMessage);
		                }
		                throw errorMsg;
		            }else{
		                if(!requestSent){
		                    requestSent = true;
		                      updateBalance(user_address);
		                    bootbox.alert("You have successfully withdrawn your vault balance");
		                }
		            }
		        }

		    }catch(err){
		       bootbox.alert(err);
		    }

		    el.html("Withdraw");
		}

	}

	async function updateBalance(address){
		var balance = await tronweb.trx.getBalance(address);
	    var trxbalance = tronweb.fromSun(balance);
	    $("#user-balance").html(trxbalance);
	}

	function initiateTronWeb() {

	    const HttpProvider = TronWeb.providers.HttpProvider;

	    /*const fullNode = new HttpProvider('https://api.shasta.trongrid.io');
	    const solidityNode = new HttpProvider('https://api.shasta.trongrid.io');
	    const eventServer = 'https://api.shasta.trongrid.io';*/

	      const fullNode = new HttpProvider('https://api.trongrid.io');
    	const solidityNode = new HttpProvider('https://api.trongrid.io');
   		 const eventServer = 'https://api.trongrid.io';

	    const tronweb = new TronWeb(
	        fullNode,
	        solidityNode,
	        eventServer,
	       "9940cb3016a36894e757969ca265d764099b34bb127d075be7d84c221eb04af7"
	    );

	    return tronweb;
	}


</script>