<?php
class User_Model extends CI_Model {
    public function insert_user($data)
    {
        return $this->db->insert('user', $data);
    }

    public function get_user_by_username($username)
    {
        return $this->db->get_where('user', ['username' => $username])->row_array();
    }

    public function get_user($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('user');
        return $query->row_array(); 
      }
  
      public function update_user($id, $data)
      {
          $this->db->where('id', $id);
          $this->db->update('user', $data);
      }
}
?>
