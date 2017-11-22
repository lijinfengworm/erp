<?php

class edbProductGet
{
	public function __construct()
	{
        $this->setFields( 'productCategory,sortName' );
	}

	public function setFields( $fields )
	{
		$this->fields = $fields;
		return $this;
	}

	public function setBeginTime( $beginTime )
	{
		$this->begin_time = $beginTime;
		return $this;
	}

	public function setEndTime( $endTime )
	{
		$this->end_time = $endTime;
		return $this;
	}

	public function setBarCode( $barCode )
	{
		$this->bar_code = $barCode;
		return $this;
	}

	public function setProductNo( $productNo )
	{
		$this->product_no = $productNo; // 注意首字母大写
		return $this;
	}

    public function setStandard($standard) {
        $this->standard = $standard;
        return $this;
    }


	public function setSortName( $sortName )
	{
		$this->sort_name = $sortName;
		return $this;
	}

	public function setBrandNo( $brandNo )
	{
		$this->brand_no = $brandNo;
		return $this;
	}

	public function setStoreId( $storeId )
	{
		$this->store_id = $storeId;
		return $this;
	}

	public function setPageNo( $pageNo )
	{
		$this->page_no = $pageNo;
		return $this;
	}

	public function setPageSize( $pageSize )
	{
		$this->page_size = $pageSize;
		return $this;
	}

	public function setIsuit($isuit) {
		$this->isuit = $isuit;
		return $this;
	}
}