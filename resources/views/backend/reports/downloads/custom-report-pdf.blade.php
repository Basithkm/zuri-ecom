<div style="margin-left:auto;margin-right:auto;">
<style media="all">
    @page {
		margin: 0;
		padding:0;
	}
	*{
		margin: 0;
		padding: 0;
	}
	body{
		line-height: 1.5;
		font-family: 'DejaVuSans', 'sans-serif';
		color: #333542;
	}
	div{
		font-size: 1rem;
	}
	.gry-color *,
	.gry-color{
		color:#878f9c;
	}
	table{
		width: 100%;
	}
	table th{
		font-weight: normal;
	}
	table.padding th{
		padding: .5rem .7rem;
	}
	table.padding td{
		padding: .7rem;
	}
	table.sm-padding td{
		padding: .2rem .7rem;
	}
	.border-bottom td,
	.border-bottom th{
		border-bottom:1px solid #eceff4;
	}
	.text-left{
		text-align:left;
	}
	.text-right{
		text-align:right;
	}
	.small{
		font-size: .85rem;
	}
	.strong{
		font-weight: bold;
	}
</style>

	@php
		$logo = get_setting('header_logo');
	@endphp



    <div style="text-align: center; padding: 20px;">
                    <img src="{{ static_asset('assets/img/Zuri_Logo.pdf.png') }}" alt="Custom Icon" style="width: 50px; height: 50px; margin-bottom: 20px;">

                    <div style="text-align: left;">
                        <div style="margin-bottom: 6px;">
                            <span style="color: #000000;">Order Number  :</span>{{$order->code}}
                        </div>
                        <div style="margin-bottom: 6px;">
                            <span style="color:#000000;">Product Name :</span>{{$product->name}}
                        </div>
                        <div style="margin-bottom: 6px;">
                            <span style="color: #000000;">Customer Name :</span>{{$user->name}}
                        </div>
                        <div style="margin-bottom: 6px;">
                            <span style="color: #000000;">Phone:</span>{{$user->phone}}
                        </div>
                    </div>

                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">



                <tr>
                    <th style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Descrpition</span></th>
                    <th style="width: 20px; height: 20px; border: 1px solid #000;">Measurement in {{$custom->measurement}}</th>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Front Neck Depth</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->front_neck_depth}}</td>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Back Neck Depth</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->back_neck_depth}}</td>                
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Upper Bust</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->upper_bust}}</td>       
                </tr>    
                    

                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Bust</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->bust}}</td>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Waist</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->waist}}</td>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Waist Round</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->waist_round}}</td>                
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Lower Waist</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->lower_waist}}</td>       
                </tr>    


                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Hip</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->hip}}</td>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Thigh Circumference</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->thigh_circumference}}</td>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Knee Circumference</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->knee_circumference}}</td>                
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Calf Circumference</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->calf_circumference}}</td>       
                </tr>    


                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Ankle Circumference</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->ankle_circumference}}</td>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Arm Hole</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->arm_hole}}</td>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Sleeve Length</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->sleeve_length}}</td>                
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Sleeve Circumference</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->sleeve_circumference}}</td>       
                </tr>   
                
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Top Length</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->top_length}}</td>
                </tr>

                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Bottom Length</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->bottom_length}}</td>
                </tr>
                <tr>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;"><span style="color: #000000;">Shoulder</span></td>
                    <td style="width: 20px; height: 20px; border: 1px solid #000;">{{$custom->shoulder}}</td>
                </tr>
               

            </table>
            <br>

            <h2 style="text-align: left; margin-top: 20px;">Note:</h2>
            <p style="text-align: left; margin: 30px;">{{$custom->order_notes}}.</p>