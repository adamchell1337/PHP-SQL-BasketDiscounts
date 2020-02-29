<?php

//creates $SESSION 
session_start();
//REQUIRES SEPERATE PHP FILE THAT CREATES CONNECTION to DB
require_once("dbcontroller.php");
//CREATES NEW OBJECT FROM DBController Class
$database_handler = new DBController();


class BasketDiscount {
	
	
	public $discounted_total_basket_cost;
	public $delivery_charge;
	
	//Initialises object with pricing rules array stored as variable
	public function __construct($pricingRules) {
		$this->pricing_rules = $pricingRules;
		
		
	}
	//Runs if called by action listener
	public function addToBasket() {
	
					//IF THERE IS A VALUE FOR QUANTITY KEY IN POST ARRAY (ADD BTN HAS BEEN CLICKED)
					if(!empty($_POST["quantity"])) {
						//RETURNS ASSOCIATIVE ARRAY FOR THE MATCHING ROW IN PRODUCT TABLE
						$database_handler = new DBController();
						$productById = $database_handler->runQuery("SELECT * FROM tblproduct WHERE id='" . $_GET["id"] . "'");
						//CREATE A MULTIDIMENSIONAL ARRAY TO STORE ALL RELEVANT DETAILS ABOUT THE PRODUCT FOUND IN PREVIOUS QUERY.
						//$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
						$itemArray = array($productById[0]["id"]=>array('name'=>$productById[0]["name"], 'id'=>$productById[0]["id"], 'quantity'=>$_POST["quantity"], 'price'=>$productById[0]["price"], 'totalprice'=>0, 'image'=>$productById[0]["image"], 'discountid'=>$productById[0]["discountid"]));
						
						
						
						//IF THE BASKET IS NOT EMPTY
						if(!empty($_SESSION["basket"])) {
							//SEARCH FOR PRODUCT ID WITHIN THE KEYS OF THE BASKET ARRAY
							if(in_array($productById[0]["id"],array_keys($_SESSION["basket"]))) {
								//echo "ITEM IS IN ARRAY ALREADY QTY+1<br>";
								//LOOP THROUGH EACH KEY/VALUE PAIR IN BASKET ARRAY
								foreach($_SESSION["basket"] as $k => $v) {
									//IF KEY MATCHES PRODUCT ID
										if($productById[0]["id"] == $k) {
											//AND IF THE QUANTITY FOR THE PRODUCT IS NOT SET, SET TO 0
											if(empty($_SESSION["basket"][$k]["quantity"])) {
												$_SESSION["basket"][$k]["quantity"] = 0;
											}
											//ADD THE POSTED QUANTITY TO THE PRODUCT'S QUANTITY VALUE 
											$_SESSION["basket"][$k]["quantity"] += $_POST["quantity"];
											
										}
								}
							//IF THE ITEM IS NOT IN THE BASKET ALREADY - MERGE ITEM ARRAY AND BASKET ARRAY	
							} else {
								$_SESSION["basket"] = array_merge($_SESSION["basket"],$itemArray);
							}
						//IF THE BASKET IS EMPTY - BASKET TAKES VALUE OF ITEM ARRAY TO FORM BASKET
						} else {
							$_SESSION["basket"] = $itemArray;
						}
					}
							
					/*
					
					//TESTING THE SYSTEM
					echo"<br> ARRAY FETCHED FROM DB: <br>";
					var_dump($productById);
					echo"<br><br> MULTIDIMENSIONAL ARRAY TO STORE CURRENT PRODUCT:<br> ";
					var_dump($itemArray);
					echo"<br><br> MULTIDIMENSIONAL ARRAY TO STORE BASKET PRODUCTS: <br>";
					var_dump($_SESSION["basket"]);
					/*echo"<br> PRICING RULES IMPORTED FROM ARRAY: <br>";
					var_dump($this->pricing_rules);
					echo"<br>";
					
					*/
					
					
	}			
	public function removeFromBasket(){
		
		
		//IF THE BASKET IS NOT EMPTY
		if(!empty($_SESSION["basket"])) {
				//LOOP THROUGH EACH ITEM ARRAY WITHIN BASKET ARRAY
				foreach($_SESSION["basket"] as $k => $v) {
					//IF IT MATCHES ID FROM URL
						if($_GET["id"] == $k)
							//print_r($_SESSION["basket"][$k]);
							//REMOVE THAT ITEM FROM THE BASKET ARAY
							unset($_SESSION["basket"][$k]);	
						//IF THE BASKET IS NOW EMPTY - DELETE BASKET ARRAY
						if(empty($_SESSION["basket"]))
							unset($_SESSION["basket"]);
							//echo "emptied";
				}
		}
		
	}
	public function emptyBasket() {		
			//EMPTY THE BASKET - DELETES THE BASKET ARRAY
		unset($_SESSION["basket"]);

	}
	public function calculateTotalPrice(){	
		//IF THE ID GIVEN IS NOT IN THE ARRAY (JUST BEEN REMOVED FROM THE BASKET) IT WILL NOT CALCULATE ANY NEW DISCOUNTS
		if (isset($_SESSION["basket"][$_GET["id"]]["discountid"])) {
			//LOOP THROUGH PRICING RULES ARRAY FOR EACH RULE
			foreach($this->pricing_rules as $key => $value) {
				//IF THE DISCOUNTID FROM THE PRICING RULES MATCHES THE DISCOUNTID OF THE CURRENT ITEM
				if ($value["discountid"] === ($_SESSION["basket"][$_GET["id"]]["discountid"])) {
					//STORES THE CORRECT DISCOUNT TO APPLY 
					$discountRule = $this->pricing_rules[$key];
					//DIVIDES ITEM QUANTITY BY QTY NEEDED TO BE ELIGIBLE FOR DISCOUNT
					$quotient = floor(($_SESSION["basket"][$_GET["id"]]["quantity"])/($this->pricing_rules[$key]["qtyneeded"]));
					//FINDS THE REMAINDER OF THE SAME CALCULATION
					$remainder = ($_SESSION["basket"][$_GET["id"]]["quantity"])%($this->pricing_rules[$key]["qtyneeded"]);
					//TOTAL DISCOUNTED COST FOR THE ITEM IS THEN CALCULATED BY MULTIPLYING QUOTIENT BY MULTI ITEM DISCOUNT PRICE, AND THEN ADDED TO REMAINDER MULTIPLIED BY THE ORIGINAL PRICE. 
					$total = ($quotient * $this->pricing_rules[$key]["discountprice"]) + ($remainder * $this->pricing_rules[$key]["originalprice"]);
					//THE DISCOUNTED TOTAL IS THEN STORED BACK INTO THE BASKET ARRAY
					$_SESSION["basket"][$_GET["id"]]["totalprice"] = $total;
				}
			}
		} else {  }
		//IF THERE IS A BASKET ARRAY STORED
		if(isset($_SESSION["basket"])){
			//SET DISCOUNTED TOTAL TO 0
			$basket_discount_total = 0;
			//FOR EACH PRODUCT IN BASKET, ADD THE ITEM'S DISCOUNTED TOTAL TO THE BASKET'S DISCOUNTED TOTAL
			foreach ($_SESSION["basket"] as $key){
				$basket_discount_total += $key["totalprice"];
			}    
			//RETURNS VALUE FROM FUNCTION TO BE DISPLAYED IN SHOPPING CART
			$this->discounted_total_basket_cost = $basket_discount_total;
			return $basket_discount_total;
			
		}
	}
	//METHOD TO CALCULATE IF A DELIVERY CHARGE IS DUE
	public function deliveryCharge(){
		//IF THE DISCOUNTED BASKET TOTAL IS LESS THAN £50
		if ($this->discounted_total_basket_cost  < 50) {
			//SET DELIVERY CHARGE T0 £7
			$this->delivery_charge = 7.00;
		} else {
			//IF TOTAL IS MORE THAN OR EQUAL TO £50 - APPLY FREE DELIVERY
			$this->delivery_charge = 0.00;
		}
	return $this->delivery_charge;
	}
	
}


//Storing the discount table from database to a pricing rules array to be passed in
$pricingRules = $database_handler->runQuery("SELECT * FROM tbldiscount");
//Creating new object from class 
$newBasket = new BasketDiscount($pricingRules);


//IF an action has been posted
if(!empty($_GET["action"])) {
	
			//switch to the relevant action
			switch($_GET["action"]) {
				
				
				//ADD AN ITEM TO THE BASKET
				case "add":
				$newBasket->addToBasket();
				$newBasket->calculateTotalPrice();
				break;
				
				//REMOVE AN ITEM FROM BASKET
				case "remove":
				$newBasket->removeFromBasket();
				$newBasket->calculateTotalPrice();
				break;
				
				//EMPTY THE BASKET
				case "empty":
				$newBasket->emptyBasket();
				break;
			}
}
?>




<HTML>


<HEAD>

<TITLE>Unidays Shopping Basket</TITLE>

<!--links in external css stylesheet-->
<link href="style.css" type="text/css" rel="stylesheet" />
</HEAD>

<BODY>
<!-- SHOPPING BASKET -->
<div id="shopping-basket">

<div class="txt-heading">Shopping Basket</div>

<!-- PUTS EMPTY BASKET ACTION INTO URL -->
<a id="btnEmpty" href="index.php?action=empty">Empty Basket</a>









<?php
//CHECK WHETHER THE BASKET ARRAY IS SET OR NOT (EXISTS)
if(isset($_SESSION["basket"])){
	//DECLARE
    $total_quantity = 0;
    $subtotal_price = 0;
?>	

<!-- TABLE TO CREATE SHOPPING BASKET -->
<table class="tbl-basket" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Product Name</th>
<th style="text-align:left;">Product ID</th>
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Individual Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	


<?php		
    foreach ($_SESSION["basket"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
				<tr>
				<td><img src="<?php echo $item["image"]; ?>" class="basket-item-image" /><?php echo $item["name"]; ?></td>
				<td><?php echo $item["id"]; ?></td>
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "£ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "£ ". number_format($item_price,2); ?></td>
				<td style="text-align:center;"><a href="index.php?action=remove&id=<?php echo $item["id"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$subtotal_price += ($item["price"]*$item["quantity"]);
		}
		?>

<tr>
<td colspan="2" align="right">Sub-Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "£ ".number_format($subtotal_price, 2); ?></strong></td>
<td></td>
</tr>
<tr>
<td colspan="2" align="right">Discounts Applied:</td>
<td colspan="3" align="right"><strong><?php echo "- £ ".number_format(($subtotal_price - $newBasket->discounted_total_basket_cost), 2); ?></strong></td>
<td></td>
</tr>
<tr>
<td colspan="2" align="right">Delivery Charge:</td>
<td colspan="3" align="right"><strong><?php 
$newBasket->delivery_charge = $newBasket->deliveryCharge();
echo "£ ".number_format($newBasket->delivery_charge, 2); ?></strong></td>
<td></td>
</tr>
<tr>
<td colspan="2" align="right">Total:</td>
<td colspan="3" align="right"><strong><?php 
$newBasket->discounted_total_basket_cost = $newBasket->calculateTotalPrice() + $newBasket->delivery_charge;
echo "£ ".number_format($newBasket->discounted_total_basket_cost, 2); ?></strong></td>
<td></td>
</tr>
</tbody>
</table>	


	
	
  <?php
  // IF THE BASKET IS EMPTY - HIDE BASKET 
} else {
?>
<div class="no-records">Your Basket is Empty</div>
<?php 
}
?>
</div>
<!-- END OF SHOPPING BASKET --->
 
 
 
 
<!-- DIV FOR PRODUCT GRID - DISPLAYS ALL PRODUCTS FOUND IN DATABASE -->
<div id="product-grid">
	<div class="txt-heading">Products</div>
	<?php
	//STORES ALL PRODUCTS IN AN ARRAY FROM THE DATABASE PRODUCTS TABLE
	$product_array = $database_handler->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
		//LOOPS THROUGH ARRAY AND CREATES A BOX FOR EACH PRODUCT IN THE DATABASE
	?>
		<div class="product-item">
			<form method="post" action="index.php?action=add&id=<?php echo $product_array[$key]["id"]; ?>">
			<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "£".$product_array[$key]["price"]; ?></div>
			<div class="basket-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Basket" class="btnAddAction" /></div>
			</div>
			</form>
		</div>
	<?php
		}
	}
	?>
</div>
</BODY>
</HTML>