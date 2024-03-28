<?php 

namespace App\Http\Traits\Communication\Email;

use App\Models\Communication\Email\EmailServer;

use Illuminate\Support\Facades\Config;

trait MenualEmailConfiguration{

   public function menualConfiguration($id) {

    $configuration = EmailServer::where('id', $id)->first();

      if ($configuration) {
          Config::set('mail.mailers.smtp2.host', $configuration->host);
          Config::set('mail.mailers.smtp2.port', $configuration->port);
          Config::set('mail.mailers.smtp2.username', $configuration->username);
          Config::set('mail.mailers.smtp2.password', $configuration->password);
          Config::set('mail.mailers.smtp2.encryption', $configuration->encryption);
          Config::set('mail.from.name', $configuration->name);
          Config::set('mail.from.address', $configuration->address);
      }
    
  }

}