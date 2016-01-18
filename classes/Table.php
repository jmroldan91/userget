<?php
/* Requere la clase pager */
class Table{
    private $headers /*array*/, $items /*array*/, $pager, $style;
    
    function __construct(Array $headers, Array $items, Pager $pager=null, $style = 'table table-stripped table-hover') {
        $this->headers = $headers;
        $this->items = $items;
        $this->pager = $pager;
        $this->order = $order;
        $this->style = $style;
    }
    
    function getHTMLTable($enlcePag, $rangoPag){
        $str =  '<table class="'.$this->style.'">';
        $str .=     '<thead><tr>';
        foreach($this->headers as $key => $value){
            $str .=     '<th>'.$value.'</th>';
        }
        $str .=     '</tr></thead>';
        $str .=     '<tbody>';
        foreach($this->items as $key => $row){
            $str .= '<tr>';
            foreach($row as $colum => $value){
                $str .= "<td>$value</td>";
            }
            $str .= '</tr>';
        }
        $str .=     '</tbody>';
        $str .= '</table>';
        if($this->pager != null){
            $str .= $this->pager->getHTMLPager($enlcePag, $rangoPag);
        }
        return $str;
    }
}