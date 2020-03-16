<?php 
$page_name="Pull Data";
function main() {
?>

<head>
<script src = "http://localhost:8000/socket.io/socket.io.js"></script>
	<script>
		var socket = io('http://localhost:8000');
		console.log(socket.emit('pulldata'));
	</script>
</head>
<body>
Requested for pulling
</body>

<?php }
include 'template-admin.php';
?>