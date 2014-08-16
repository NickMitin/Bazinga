<?php



class bmTestPattern extends bmCustomRemoteProcedure
{
	/*FF::AC::CGIPROPERTIES::{*/
	
  public $subject;
  public $pattern;
  
  /*FF::AC::CGIPROPERTIES::}*/


  public function __construct($application, $parameters = array())
  {
    $this->type = BM_RP_TYPE_JSON;
    $this->output = new stdClass();
    $this->output->result = '';

    parent::__construct($application, $parameters);
  }

  public function execute()
  {
    if ((mb_strlen($this->subject) > 0) && (mb_strlen($this->pattern) > 0))
    {
      ob_start();

      $matches = array();
      preg_match_all($this->pattern, $this->subject, $matches);

      var_dump($matches);

      $this->output->result = ob_get_clean();
    }

    return parent::execute();
  }
}
?>
