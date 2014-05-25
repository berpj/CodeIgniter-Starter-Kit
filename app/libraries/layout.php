<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    private $CI;
    private $theme = 'default';
    private $var = array();
    
    public function __construct()
    {
        $this->CI = get_instance();

        $this->CI->load->library('parser');
        $this->CI->load->library('session');

        $this->var['css'] = array(
         array('url' => '/assets/css/bootstrap.min.css'),
         array('url' => '/assets/css/style.css')
         );

        $this->var['js'] = array(
            array('url' => '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'),
            array('url' => '/assets/js/bootstrap.min.js')
            );

        $this->var['title'] = '';
        $this->var['h1'] = '';
        $this->var['body'] = '';
        $this->var['msg'] = '';

        if (($this->CI->session->flashdata('msg')))
            $this->set_msg($this->CI->session->flashdata('msg'));

        if (($this->CI->session->flashdata('msg_error')))
            $this->set_msg($this->CI->session->flashdata('msg_error'), true);
    }

    public function set_layout($file)
    {
        if (is_string($file) AND !empty($file) AND file_exists('./app/templates/' . $file . '.php'))
        {
            $this->theme = $file;
            return true;
        }
        return false;
    }

    public function set_titles($title, $h1 = '')
    {
        $this->var['title'] = $title;
        $this->var['h1'] = $h1;
    }

    public function set_msg($msg, $is_error = false)
    {
        if (!$is_error)
            $this->var['msg'] = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button>' . $msg . '</div>';
        else
            $this->var['msg'] = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">×</button>' . $msg . '</div>';
    }

    public function views($name, $data = array())
    {
        $this->var['body'] .= $this->CI->parser->parse($name, $data, true);
        return $this;
    }

    public function view($name, $data = array())
    {
        $data = array_merge($this->var, $data);
        $data['header'] = $this->CI->parser->parse('../templates/part.header.php', $data, true);
        $data['body'] .= $this->CI->parser->parse($name, $data, true);
        $data['footer'] = $this->CI->parser->parse('../templates/part.footer.php', $data, true);
        $this->CI->parser->parse('../templates/' . $this->theme, $data);
    }
}
