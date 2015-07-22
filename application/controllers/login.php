<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 21-Jul-15
 * Time: 13:43
 */


class Login extends CI_Controller {
    function  index() {
        $data['main_content'] = 'login_form';
        $this->load->view('includes/template', $data);
    }

    function validate_credentials() {
        $this->load->model('membership_model');
        $query = $this->membership_model->validate();
        if ($query == false) {
            $this->index();
        }
        else {
            $data = array(
                'username' => $this->input->post('username'),
                'is_logged_in' => true
            );


            $this->session->set_userdata($data);

            // now redirect the user to the site controller
            // and to a method named members_area
            redirect('site/members_area');
        }
    }

    function signup()
    {
        $data['main_content'] = 'signup_form';
        $this->load->view('includes/template', $data);
    }

    function create_member() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');

        if ($this->form_validation->run() == false) {
            $this->load->view('signup_form');
        }
        else {
            $this->load->model('membership_model');
            if ($query = $this->membership_model->create_member()) {
                $data['main_content'] = 'signup_successful';
                $this->load->view('includes/template', $data);
            }
            else {
                $this->signup();
            }
        }
    }

}