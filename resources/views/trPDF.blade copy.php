<html>
<head>
  <style>
    @page { margin: 100px 25px; }
    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
  </style>
</head>
<body>
  <header>
    <title>TR - {{$tr[0]['mr']['orderno']}}</title>
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
  <footer>footer on each page</footer>
  <main>
    <p> <table class="table" width="100%" style="height:500px;page-break-after: always;" border="1">
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
                if($key + 1 > 22) break;?>
            
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$x['item']['item_no']}}</td>
                <td>{{$x['item']['description']}}</td>
                <td>{{$x['item']['uom']}}</td>
                <td>{{$x['qty']}}</td>
            </tr>
           <?php   } ?>
        </tbody>
        </table></p>
    <p>page2></p>
  </main>
</body>
</html>