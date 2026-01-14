#!/bin/sh

set -e

cd /var/www

echo "🔍 Verificando dependências..."

if [ ! -d "vendor" ]; then
  echo "📦 vendor não encontrado, rodando composer install..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo "✅ vendor já existe, pulando composer install"
fi

echo "🚀 Iniciando aplicação..."
exec "$@"
