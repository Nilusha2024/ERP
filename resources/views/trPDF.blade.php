<!DOCTYPE html>
<html>
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TR -  <?php if($tr[0]['type'] == 1){ ?>
                    <td>DIRECT</td>
                <?php }else{ ?>
                    <td>{{$tr[0]['mr']['orderno']}}</td>
                <?php } ?>
        </title>
   
</head>
<body>
    <div style="margin: 0 auto;display: block;width: 700px;">
        <header>
        <table  class="table table-striped;font-size:12px" width="100%" >
            <thead>
            <tr>
                <td style="text-align:left"><strong>To Location - {{$tr[0]['to']['location']}}</strong></td>
                <td colspan="1" style="text-align:right">
                    <img src="http://localhost/gatekeeper/public/image/lsh-logo.jpg" style="width:100px;"> 
                </td>
            </tr>
            </thead>
        </table>   
        <table  class="table table-striped;font-size:12px" width="100%" >
        <thead>
            <tr>
            <td style="text-align:center;"><strong>Transfer Order</strong></td>
            </tr>
        </thead>
        </table>
        <table  class="table table-striped;font-size:12px" width="100%" >
            <thead>
           
            <tr>
                <td>Transfer Order No : {{$tr[0]['tr_no']}}</td>
                <td>From Location: {{$tr[0]['from']['location']}}</td>   
            </tr>
            <tr>
                <?php if($tr[0]['type'] == 1){ ?>
                    <td>Material Request NO: DIRECT</td>
                <?php }else{ ?>
                    <td>Material Request NO: {{$tr[0]['mr']['orderno']}}</td>
                <?php } ?>
                <td>Contact: 0112421293/94 - 094761417555</td>
            </tr>
            <tr>
                <td>Date: {{$tr[0]['created_at']}}</td>
                <td></td>
            </tr>
        </thead>
       
        </table>
        </header>
        <br>
        <br>
        <main>
       
       <table class="table" width="100%" style="height:500px;" border="1">
        <tbody>
            <tr style="background-color:gray">
                <td>#</td>
                <td>Item Code</td>
                <td>Item Name</td>
                <td>UOM</td>
                <td>Qty</td>
            </tr>       
            <?php 
            foreach($tr[0]['stdetails'] as $key =>  $x){ 
                ?>
          
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$x['item']['item_no']}}</td>
                <td>{{$x['item']['description']}}</td>
                <td>{{$x['item']['uom']}}</td>
                <td>{{$x['qty']}}</td>
            </tr>
           <?php  
                } 
                ?>
        </tbody>
        </table>
       <br></br>
       
       <footer>
       <table  style="margine-top:100px"class="table" width="100%" >
        <tbody>
            <tr>
                <td>Prepare by. ……………………….</td>
                <td>Cheked by:……………………….</td>
                <td>Approved by:…………………</td>
            </tr>
        </tbody>
        </table>
        <br>
        <br>
        <table class="table" width="100%" >
        <tbody>
            <tr>
                <td>Received in Good Condition</td>
            </tr>
        </tbody>
        </table>
        <br>
        <br>
        <table class="table" width="100%" >
        <tbody>
            <tr>
                <td>Signature:……………………….</td>
                <td>Name:…………………</td>
                <td>Date:…………………</td>
            </tr>
        </tbody>
        </table>
        </footer>
    </div>
</body>
</html>