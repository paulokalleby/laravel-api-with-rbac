#!/bin/sh
set -e

cd /var/www

echo "🔍 Verificando dependências..."

if [ ! -f "vendor/autoload.php" ]; then
  echo "📦 vendor inválido ou ausente, rodando composer install..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "✅ vendor válido, pulando composer install"
fi

echo "🚀 Iniciando aplicação..."
exec "$@"
