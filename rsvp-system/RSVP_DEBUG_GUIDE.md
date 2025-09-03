# 🐛 RSVP Form Debug Guide

## Problema Identificado
Os botões RSVP apenas recarregam a página sem salvar a resposta.

## 🔍 Soluções Implementadas

### 1. Versão de Debug Criada
Criei `invitation-debug.blade.php` que inclui:
- ✅ Informações de debug visíveis
- ✅ Formulário simplificado sem JavaScript
- ✅ Botões de teste manual
- ✅ Logs detalhados no console

### 2. Logs Adicionados
O controlador agora registra:
- ✅ Tentativas de submissão
- ✅ Dados recebidos
- ✅ Status de validação
- ✅ Erros encontrados

### 3. Rota de Debug
Adicionada rota GET para testar sem formulário:
- `/invite/{token}/rsvp?rsvp_status=yes&_token={csrf_token}`

## 🚀 Como Testar e Corrigir

### Passo 1: Execute o Script de Teste
```bash
cd /www/wwwroot/fileserver.corenexa.it.com
chmod +x test-rsvp.sh
./test-rsvp.sh
```

### Passo 2: Verifique a Página de Debug
1. Acesse um link de convite
2. A página agora mostra informações de debug
3. Verifique se:
   - Token está correto
   - Status é "pending"
   - "Has Responded" é "No"
   - "Invitation Valid" é "Yes"

### Passo 3: Teste os Botões
1. Abra o Console do Navegador (F12)
2. Clique em um botão RSVP
3. Verifique os logs no console
4. Verifique se há erros JavaScript

### Passo 4: Teste Manual
Use os botões "Test Yes (GET)" para testar sem formulário

### Passo 5: Verifique os Logs Laravel
```bash
cd /www/wwwroot/fileserver.corenexa.it.com
tail -f storage/logs/laravel.log
```
Depois clique nos botões RSVP e veja os logs em tempo real.

## 🔧 Possíveis Causas e Soluções

### Causa 1: Problema de CSRF Token
**Sintomas**: Página recarrega sem ação
**Solução**:
```bash
# Limpe o cache de configuração
php artisan config:clear
php artisan cache:clear
```

### Causa 2: Middleware Bloqueando
**Sintomas**: Formulário não chega ao controlador
**Solução**: Verificar se há middleware interferindo nas rotas públicas

### Causa 3: JavaScript Interferindo
**Sintomas**: Formulário é interceptado pelo JS
**Solução**: A versão de debug remove todo JavaScript complexo

### Causa 4: Problemas de HTTPS/Mixed Content
**Sintomas**: Formulário não submete por questões de segurança
**Solução**:
```bash
# Force HTTPS
echo "FORCE_HTTPS=true" >> .env
php artisan config:cache
```

### Causa 5: Problemas de Permissão
**Sintomas**: Erro 500 ou logs não funcionam
**Solução**:
```bash
sudo chmod -R 775 storage
sudo chown -R www-data:www-data storage
```

## 🎯 Teste Rápido

### Teste Direto via URL (GET)
Substitua `{token}` pelo token real:
```
https://fileserver.corenexa.it.com/invite/{token}/rsvp?rsvp_status=yes&_token={csrf_token}
```

### Teste via CURL
```bash
curl -X POST https://fileserver.corenexa.it.com/invite/{token}/rsvp \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "rsvp_status=yes&_token={csrf_token}"
```

## 📋 Checklist de Verificação

Execute estes comandos no seu servidor:

```bash
cd /www/wwwroot/fileserver.corenexa.it.com

# 1. Verificar se o banco está funcionando
php artisan tinker --execute="echo App\Models\Guest::count();"

# 2. Verificar rotas
php artisan route:list | grep rsvp

# 3. Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 4. Verificar logs
tail -20 storage/logs/laravel.log

# 5. Testar submissão manual
php artisan tinker --execute="
\$guest = App\Models\Guest::where('rsvp_status', 'pending')->first();
if (\$guest) {
    \$guest->update(['rsvp_status' => 'yes', 'rsvp_confirmed_at' => now()]);
    echo 'Manual update successful. New status: ' . \$guest->fresh()->rsvp_status;
} else {
    echo 'No pending guests found';
}
"
```

## 🔄 Reverter para Versão Original

Quando o problema for resolvido, reverta para a versão original:

```php
// No RsvpController.php, linha 33:
return view('rsvp.invitation', compact('guest'));
```

## 📞 Próximos Passos

1. Execute o script de teste
2. Acesse a página de debug
3. Verifique os logs do navegador e Laravel
4. Teste os botões manuais
5. Reporte os resultados encontrados

A versão de debug deve ajudar a identificar exatamente onde o problema está ocorrendo!