<?php
$db=new db_connection();
$result=$db->query("SELECT * FROM pydatabase.booking_table order by Time desc LIMIT 1");
echo "<div style='position: fixed; bottom: -23;color: chocolate; font-weight:bolder; width: 100%;  padding: 10px; text-align: center;'><h3><a href='../HTML/index.html' style='color: chocolate;font-weight:bolder;font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;'>Home</a><h3></div>";
?>
<style>
  body {
    background-image: url('../IMAGE/img.jpg');
    background-size: cover;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
   
    text-align:center;
  }
  th, td {
    border: 1px solid black;
    padding: 10px;
    background-color: rgb(152, 150, 148);
    color: black;
    font-weight:bolder
  }
  th {
    background-color: rgb(119, 115, 113);
    color: black;
    font-weight:bolder
  }
  tr:nth-child(even) {
    background-color: #FFF8DC;
  }
  h1 {
    color: black;
    margin-top: 170px;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
</style>
<center><h1>Booking Confirmed...!</h1></center>
<table>
  <thead>
    <tr>
      <th>From</th>
      <th>To</th>
      <th>Departure</th>
      <th>Return</th>
      <th>Adults</th>
      <th>Childrens</th>
      <th>Class</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (mysqli_num_rows($result) > 0) {
      $sn=1;
      while($data = mysqli_fetch_assoc($result)) {
     ?>
     <tr>
       <td><?php echo $data['Fly_From']; ?></td>
       <td><?php echo $data['Fly_To']; ?></td>
       <td><?php echo $data['Departing']; ?></td>
       <td><?php echo $data['Returning']; ?></td>
       <td><?php echo $data['Adults']; ?></td>
       <td><?php echo $data['Children']; ?></td>
       <td><?php echo $data['Class']; ?></td>
       <td><?php echo $data['Amount']."/-"; ?></td>
     </tr> 
     <?php
      $sn++;
      }
    } else {
     ?>
      <tr>
       <td colspan="8">No data found</td>
      </tr>
     <?php
}
?>
</table>