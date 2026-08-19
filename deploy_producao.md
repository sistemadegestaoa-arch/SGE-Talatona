# Guia de Deployment em Produção — Kifica
> Preserva todos os dados existentes. Seguro para executar em produção.

---

## ⚠️ ANTES DE COMEÇAR — BACKUP OBRIGATÓRIO

Execute este comando no servidor de produção para fazer backup da base de dados:

```bash
# Substituir: utilizador, senha, nome_da_bd, caminho_de_backup
mysqldump -u root -p kifica > backup_kifica_$(date +%Y%m%d_%H%M%S).sql
```

**Guardar o ficheiro de backup num local seguro antes de continuar.**

---

## O QUE MUDA NA BASE DE DADOS

As migrations abaixo vão **ADICIONAR** novas tabelas e colunas.  
As tabelas existentes (`produto`, `estoque`, `requisicao`, `users`, etc.) **NÃO são alteradas nem apagadas**.

| Migration | O que faz |
|-----------|-----------|
| `2026_05_20_000001_create_atendimento_table` | Cria tabela `atendimento` |
| `2026_06_06_000001` a `000008` | Cria módulo hospitalar (paciente, episodio, triagem, consulta, exames, receita) |
| `2026_06_14_000001_add_bloqueado_to_produto_table` | Adiciona colunas `bloqueado`, `motivo_bloqueio`, `bloqueado_por`, `bloqueado_em` à tabela `produto` — **dados existentes preservados, novas colunas ficam a NULL/false** |
| `2026_06_15_000001_create_requisicao_farmaco_table` | Cria tabela de requisições de fármacos |
| `2026_06_15_000002_add_urgente_to_episodio_table` | Adiciona coluna `urgente` à tabela `episodio` |
| `2026_06_15_000003_add_senha_chamada_to_episodio_table` | Adiciona colunas `senha`, `chamado_em` à tabela `episodio` |
| `2026_06_15_000004_create_prescricao_table` | Cria tabelas de prescrição médica |

---

## PASSOS DE DEPLOYMENT

### 1. Fazer backup (OBRIGATÓRIO)
```bash
mysqldump -u root -p kifica > backup_kifica_antes_deploy.sql
```

### 2. Copiar os ficheiros actualizados para o servidor
Copiar todo o projecto para o servidor de produção (excluindo `.env` e `vendor`).

### 3. Activar modo manutenção (opcional mas recomendado)
```bash
php artisan down --message="Actualização em curso. Volte em breve." --retry=60
```

### 4. Instalar/actualizar dependências
```bash
composer install --no-dev --optimize-autoloader
```

### 5. Executar as migrations
```bash
php artisan migrate --force
```
> O `--force` é necessário em produção (confirma automaticamente).  
> As migrations já executadas (Batch 1 e 2) são **ignoradas** automaticamente pelo Laravel.  
> Só as novas migrations são executadas.

### 6. Limpar caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
```

### 7. Desactivar modo manutenção
```bash
php artisan up
```

---

## ROLLBACK DE EMERGÊNCIA (se algo correr mal)

Se houver problemas após o deployment, restaurar o backup:

```bash
# 1. Activar manutenção
php artisan down

# 2. Restaurar base de dados
mysql -u root -p kifica < backup_kifica_antes_deploy.sql

# 3. Reverter código para a versão anterior
git checkout HEAD~1   # ou restaurar o zip da versão anterior

# 4. Reactivar
php artisan up
```

---

## VERIFICAÇÃO PÓS-DEPLOYMENT

Após o deployment, verificar:

```bash
# Ver estado das migrations — todas devem mostrar "Yes"
php artisan migrate:status

# Verificar se a aplicação arranca sem erros
php artisan route:list | head -20
```

Testar no browser:
- [ ] Login funciona
- [ ] Lista de fármacos abre
- [ ] Perfil do utilizador abre
- [ ] (Se aplicável) Módulo hospitalar — triagem, consultas, laboratório

---

## NOTAS IMPORTANTES

- **`php artisan migrate`** é seguro — nunca apaga dados existentes quando só adiciona tabelas/colunas
- As colunas novas adicionadas a tabelas existentes têm valores `default` ou `nullable` — os registos existentes ficam com `false`/`null` automaticamente
- O `.env` de produção **NÃO deve ser substituído** — tem as credenciais da BD de produção
