<!DOCTYPE html>
<?php 
session_start();
include("dataconnection.php"); ?>
<html>
<head>
	<title>Dashboard |LDK Sports Admin</title>
	<link rel="icon" href="../image/logo.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="admin_general_design.css">
	<style>
		*{
			box-sizing: border-box;
		}
		
		body{
			position:relative;
		}
		/**************ADMIN CONTENT***************/
		.admin-content {
			margin-top: 37.3px;
			margin-left:18%;
			padding-top: 5px;
			z-index: 0;
			width:81.5%;
			height : 100%;
			overflow: hidden;
		}
		.admin-content .content1{
			padding: 0;
			margin:0;
			width:100%;
			height:150px;
			
		}
		.admin-content .content1 .admin-box{
			float: left;
			display: block;
			margin:15px 10px;
			height: 117px;
			width: 200px;
			border-radius: 3px;
			overflow: hidden;
		}
		.admin-content .content1 #total-profit{
			width:300px;
		}
		.admin-content .content1 .admin-box:hover{
			box-shadow: 5px 5px 10px grey;
			cursor: pointer;
		}
		.admin-content .content1 .admin-box h2{
			font-size: 20px;
			font-weight: bold;
			margin: 4px 0px 3px 4px;
			color: white;
		}
		.admin-content .content1 .admin-box h3{
			float:right;
			font-size: 70px;
			font-weight: bold;
			color:#f5f5f5;
			margin-top: 11px;
		}
		/*****************REPORTS*********************/
		.admin-content .report{
			float:left;
			height: 400px;
			width:77%;
			margin:20px 5px;
		}
		.admin-content .report h1{
			margin:0;
			font-size:30px;
			padding-left:10px;
		}
		/***************HOT SALES*********************/
		.admin-content .hot-sale{
			float:left;
			height: 300px;
			width:98%;
			margin:20px 5px;
			
		}
		.admin-content .hot-sale h1{
			font-size: 30px;
			font-weight: bold;
			padding-left:20px;
		}
		/********************NEWS*********************/
		.admin-content .news{
			float:right;
			height: 400px;
			width:20%;
			margin:20px 5px;
			border:1px solid grey;
			border-radius: 3px;
		}
		.admin-content .news h1{
			font-size: 16px;
			margin: 5px 0px 3px 5px;
			font-weight: bold;

		}
		.admin-content .news ul{
			
			margin:0px;
			padding:5px;
		}
		.admin-content .news ul li{
			margin:15px 0px;
			padding:0px;
		}
		.admin-content .news ul li a {
			color: black;
			text-decoration: none;
			margin:0px;
			font-size: 15px;
		}
		.admin-content .news ul li a:hover{
			color:cadetblue;
			text-decoration: underline;
		}
		/*****************RESPONSIVE******************/
		@media screen and (max-width:830px){
			
			.admin-content .content1{
				width: 100%;
			}
			.admin-content .news{
				width:90%;
				float:left;
			}
			.admin-content .report{
				width:90%;
			}
			.admin-content .content1 #total-profit{
				width:420px;
			}
		}
		/***************hot-sales-product*******************/
		.allproducts{
		background-color:white;
	
	}
		.allproducts .card{
		background-color: white;
		border-radius:10px;
		padding:1em;
		box-shadow: 0px 10px 5px #d1ccc0;
		text-align: center;
	}
	
	.card .title{
		font-size:15px;
	}
	

	.card .text1{
		font-size:12px;
		margin-top:-1em;
		font-style:italic;
	}
	
	
	.card .text2{
		font-size:12px;
		margin-top:-1em;
	}
	.addtocart {
		background-color:#2d3436;
		border:none;
		padding:1em;
		border-radius: 5px;
		font-size:10px;
		width: 50%;
		color:white;
	}
	
	.addtocart:hover{
		background-color:#636e72;
		padding:1em;
		border-radius: 5px;
		font-size:10px;
		color:white;
		box-shadow: 0px 5px 5px #b2bec3;
	}
	
	.product-container{
		display:grid;
		grid-template-columns:1fr 1fr 1fr 1fr;
		grid-column-gap: 20px;
		grid-row-gap:60px;
		max-width:90%;
		margin:auto;
	}

    </style>
	<script type="text/javascript">
		window.onload = function () {
		  var chart = new CanvasJS.Chart("chart-box",
		  {
			 data: [
			{
			  type: "line",
			  lineColor:"#c9b06d",
			  markerColor:"#c9b06d",
	  
			  dataPoints: [
			  { x: new Date(2024, 01, 1), y: 0 },
			  { x: new Date(2024, 02, 1), y: 0 },
			  { x: new Date(2024, 03, 1), y: 0 },
			  { x: new Date(2024, 03, 1), y: 0 },
			  { x: new Date(2024, 05, 1), y: 0 },
			  { x: new Date(2024, 06, 1), y: 0 },
			  { x: new Date(2024, 07, 1), y: 0 },
			  { x: new Date(2024, 08, 1), y: 0 },
			  { x: new Date(2024, 09, 1), y: 0 },
			  { x: new Date(2024, 10, 1), y: 0 },
			  { x: new Date(2024, 11, 1), y: 0 },
			  { x: new Date(2024, 12, 1), y: 0 }
			  ]
			}
			]
		  });
	  
		  chart.render();
		}
	</script>
	<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<body style="margin:0;">
	<header>
		<img src="../image/logo.png" alt="LDK Sports" class="logo">
		<ul>
			<li><h3>Hello, <?php echo $_SESSION['a_name']; ?></h3></li>
			<li><a href="edit_admin.php?edit&admin_id=<?php echo $_SESSION['a_id']; ?>" ><i class="glyphicon glyphicon-user"></i></a></li>
			<li><a href="admin_logout.php" ><i class="glyphicon glyphicon-log-out"></i></a></li>
		</ul>
	</header>

	<div class="admin-title">
			<div class="breadcrumb">
			<ul>
				<li><a href="#top">Dashboard</a></li>
			</ul>
		</div>
		<a href="admin_index.php" class="home-icon"><i class="glyphicon glyphicon-home"></i></a>
	</div>

	<?php include("admin_nav.php"); ?>

	<div class="admin-content">
		<div class="content1">
			<a href="">
				<div  class="admin-box" style="background-color:#e86375;">
					<h2>Total Users</h2>
					<?php
					$result = mysqli_query($conn, "SELECT * FROM user");	
					$count = mysqli_num_rows($result);?>
					<h3><?php echo str_pad($count,2,"0",STR_PAD_LEFT); ?></h3>
				</div>
			</a>

			<a href="">
				<div class="admin-box" style="background-color:#ffa199;">
					<h2>Total Sales</h2>
					<?php
					$result = mysqli_query($conn, "SELECT * FROM sales");	
					$count = mysqli_num_rows($result);?>
					<h3><?php echo str_pad($count,2,"0",STR_PAD_LEFT); ?></h3>
				</div>
			</a>

			<a href="">
				<div class="admin-box" style="background-color:#ffd099;">
					<h2>Total Reviews</h2>
					<?php
					$result = mysqli_query($conn, "SELECT * FROM feedback");	
					$count = mysqli_num_rows($result);?>
					<h3><?php echo str_pad($count,2,"0",STR_PAD_LEFT); ?></h3>
				</div>
			</a>
			
			<a href="">
				<div id="total-profit" class="admin-box" style="background-color:#afd6ad;">
					<h2>Total Profit</h2>
					<?php
					$result = mysqli_query($conn, "SELECT SUM(order_total_price) AS sum_profit FROM orders");
					$row = mysqli_fetch_assoc($result);
					;?>
					<h3 style="font-size: 50px;margin-top: 30px;margin-left: 5px;float:left;">RM</h3>
					<h3 style="font-size: 50px;margin-top: 30px;"><?php echo $row['sum_profit']; ?></h3>
				</div>
			</a>
		</div>

		<div class="news">
			<h1>News & Anouncements</h1>
			<ul>
				<li><a href="http://covid-19.moh.gov.my/terkini">
Situasi Terkini COVID-19 di Malaysia</a></li>
				<li><a href="https://news.yahoo.com/malaysia-approves-sinovac-astrazeneca-covid-121058890.html">Malaysia approves Sinovac, AstraZeneca COVID-19 vaccines for use</a></li>
				<li><a href="https://www.oedigital.com/news/485667-malaysia-petronas-launches-offshore-bid-round">Malaysia Petronas Launches Offshore Bid Round</a></li>
			</ul>
		</div>

		<div class="report">
			<h1>Sales Statistics</h1>
			<div id="chart-box" style="height: 300px; width: 100%;"></div>
		</div>

		<div class="hot-sale">
			<h1>HOT SALES</h1>

			<section class="hot-sales-product">
				<div class="product-container">
				<?php
					
					$result = mysqli_query($conn, "SELECT* FROM product LIMIT 5");	
					$count = mysqli_num_rows($result);
					
					while($row = mysqli_fetch_assoc($result))
					{
						$img_src = $row['product_img'];
				?>
					<div class="card">
					<div class="image"><img src="<?php echo '../'.$img_src;?>" style="height: 100px;"/></div>
					<div class="title"><?php echo $row['product_name'];?></div>
					<div class="text2"><p>Price: <?php echo "RM", $row['product_price'];?></p></div>
					
					<input type="button" class="edit-product" onClick="location.href='updateproduct.php?edit&product_id=<?php echo $row["product_id"];?>'" value="EDIT"/>
					</div>
				<?php 
					}
		 		?>
				</div>
			</section>
		</div>
	</div>


	<script>
	</script>
</body>
</html>