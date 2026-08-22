<?php
declare(strict_types=1);

require_once __DIR__ . '/../Core/Model.php';

use App\Core\Model;


class PlanoModel extends Model
{
   protected $table = 'planos';

   public function contarTotal(): int
   {
      $sql = "SELECT COUNT(*) as total FROM {$this->table}";
      $stmt = $this->db->query($sql);
      $resultado = $stmt->fetch();
      return (int) ($resultado['total'] ?? 0);
   }
}