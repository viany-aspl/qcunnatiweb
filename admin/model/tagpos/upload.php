<?php
class ModelTagposUpload extends Model 
{
	public function readExcel($data,$unit_id,$filter_date,$filename='',$main_heading='',$sub_heading='') 
	{ 
		$log=new Log("UploadSalesExcel-".date('Y-m-d').".log");
		$filter_date=date('Y-m-d',strtotime($filter_date));
		$get_file= $this->db->query("select * from oc_indent_excel_file where indent_date='".$filter_date."' and unit_id='".$unit_id."' ");
		$log->write($get_file->num_rows);
		if($get_file->num_rows>0)
		{
			
			$file_id=$get_file->row['sid'];
			$log->write($file_id);
		}
		else
		{
			$sql1="insert into ".DB_PREFIX . "indent_excel_file set file_name='".$filename."',date_added='".date('Y-m-d h:i:s')."',
			unit_id='".$unit_id."',indent_date='".$filter_date."',main_heading='".$main_heading."',sub_heading='".$sub_heading."' ";
			//exit;
			$log->write($sql1);
			
			$query = $this->db->query($sql1);
			$file_id=$this->db->getLastId();
		}
		$log->write($file_id);
		$a=0;
		
		$uploaded=0;
		$duplicate=0;
		$total=0;
		$error=0;
		foreach($data as $key=>$val)
		{
			$value= array_filter($val);
			
			if(empty($value))
			{
				continue;
				$error++;
			}
			if(empty($value[0]))
			{
				continue;
				$error++;
			}
			
			if(strlen($value[0])!==7)
			{
				//return 'Wrong file';
				return array('file_id'=>'Wrong file','uploaded'=>$uploaded,'duplicate'=>$duplicate,'total'=>$total,'error'=>$error);
				//exit;
			}
			
			//echo '<br><br>';
			$a++;
			//continue;
			//echo 'here';
			//exit;
			
			$log->write($value);
			if(!empty($value[0]))
			{
			/*
			$sql="insert into ".DB_PREFIX . "indent_excel set file_id='".$file_id."',OFC='".$value['0']."',
			OFFICER='".$value['1']."',MOC='".$value['2']."',MOTIVATOR='".$value['3']."',
			VCD='".$value['4']."',GCD='".$value['5']."',NAME='".$value['6']."',
			FATHER='".$value['7']."',INDENT_NO='".$value['8']."',Item='".$value['9']."',
			Qty='".$value['10']."',Rate='".$value['11']."',ADV_AMT='".$value['12']."',
			HELD_AMT='".$value['13']."',ACTI_AMT='".$value['14']."',RET_AMT='".$value['15']."',CP_AMT='".$value['16']."',date_added='".date('Y-m-d h:i:s')."',
			unit_id='".$unit_id."',indent_date='".$filter_date."'
			";
			*/
			
			$get_indent= $this->db->query("select * from oc_indent_excel where  unit_id='".$unit_id."' and INDENT_NO='".$value['0']."' ");
			$log->write($get_indent->num_rows);
			if($get_indent->num_rows>0)
			{
				$duplicate++;
			}
			else
			{
				$sql="insert into ".DB_PREFIX . "indent_excel set file_id='".$file_id."',INDENT_NO='".$value['0']."',MOC='".$value['1']."',
				MOTIVATOR='".$value['2']."',Item='".$value['3']."',Qty='".$value['4']."',Rate='".$value['5']."',
				date_added='".date('Y-m-d h:i:s')."',unit_id='".$unit_id."',indent_date='".$filter_date."' ";
				$log->write($sql);
			
				$query2 = $this->db->query($sql);
				$uploaded++;
			}
			$total++;
			}
			
		}
		return array('file_id'=>$file_id,'uploaded'=>$uploaded,'duplicate'=>$duplicate,'total'=>$total,'error'=>$error);
	}
	public function get_file_id($unit_id,$filter_date) 
	{
		$sql="select * from oc_indent_excel_file where unit_id='".$unit_id."' and date(indent_date)='".$filter_date."' order by sid desc limit 1 ";
		
		$query= $this->db->query($sql);
		return $query->row['sid'];
	}
	public function getdata($data) 
	{	
		$sql="select * from oc_indent_excel where file_id!='' ";
		if($data['billing']==1)
		{
			$sql.=" and billing_status='1' ";
		}
		if($data['billing']==2)
		{
			$sql.=" and billing_status='0' ";
		}
		if($data['INDENT_NO']!='')
		{
			$sql.=" and INDENT_NO='".$data['INDENT_NO']."' ";
		}
		else
		{
			$sql.=" and file_id='".$data['file_id']."' ";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql;
		$query= $this->db->query($sql);
		return $query->rows;
	}
	public function getTotaldata($file_id,$billing='',$INDENT_NO='') 
	{
		$sql="select * from oc_indent_excel where file_id!='' ";
		
		if($billing==1)
		{
			$sql.=" and billing_status='1' ";
		}
		if($billing==2)
		{
			$sql.=" and billing_status='0' ";
		}
		if($INDENT_NO!='')
		{
			$sql.=" and INDENT_NO='".$INDENT_NO."' ";
		}
		else
		{
			$sql.=" and file_id='".$file_id."' ";
		}
		//echo $sql;
		$query= $this->db->query($sql);
		return $query->num_rows;
	}
}