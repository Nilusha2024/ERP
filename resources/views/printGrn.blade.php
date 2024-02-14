<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>GRN - {{$grn[0]['grn_no']}}</title>
</head>
<body>
    <div style="margin: 0 auto;display: block;width: 700px;">
        <table  class="table table-striped;font-size:12px" width="100%" >
        <thead>
        <tr>
                
                <td colspan="1" style="text-align:right">
                    <img src="http://localhost/gatekeeper/public/image/lsh-logo.jpg" style="width:100px;"> 
                </td>
            </tr>
            <tr>
            <td style="text-align:center;background-color:gray"><strong>Good Received Note</strong></td>
            </tr>
        </thead>
        </table>
        <table  class="table table-striped;font-size:12px" width="100%" >
            <thead>
           
            <tr>
                <td>Good Received Note : {{$grn[0]['grn_no']}}</td>
                <td>From : Main Warehouse L.S. - Colombo</td>
            </tr>
            <tr>
                <td>Purchase Order NO: {{$grn[0]['po']['po_no']}}</td>
                <td>445/1, Sirimawo Bandaranayaka mw,</td>
            </tr>
            <tr>
                <td>Date: {{$grn[0]['created_at']}}</td>
                <td>Colombo 14.</td>
            </tr>
            <tr>
                <td>Vendor: {{$grn[0]['po']['vendor']['name']}}</td>
                <td>0112421293/94</td>
            </tr>
            <tr>
                <td></td>
                <td>Phone No.  094761417555</td>
            </tr>
        </thead>
       
        </table>
        <br>
        <br>
        <table class="table" width="100%"  border="1">
        <tbody>
            <tr style="background-color:gray">
                <td>#</td>
                <td>Item Code</td>
                <td>Item Name</td>
                <td>UOM</td>
                <td>Price</td>
                <td>Qty</td>
                <td>Amount</td>
            </tr>
            <?php $total = 0;?>
            <?php foreach($grn[0]['grndetails'] as $key =>  $x){ 
                $amount =  $x['price'] * $x['qty'];
                 $total += $amount;
            ?>
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$x['item']['item_no']}}</td>
                <td>{{$x['item']['description']}}</td>
                <td>{{$x['item']['uom']}}</td>
                <td>{{$x['price']}}</td>
                <td>{{$x['qty']}}</td>
                <td style="text-align:right">{{number_format($amount, 2, '.', ',')}}</td>
            </tr>
           <?php } ?>
           <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total</td>
                <td style="text-align:right">{{number_format($total, 2, '.', ',')}}</td>
            </tr>
        </tbody>
        </table>
       <br></br>
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
    </div>
</body>
</html>

{{-- re importing jQuery because it won't load for some reason  --}}
    <script src="plugins/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            window.print();
           
        });
    </script>