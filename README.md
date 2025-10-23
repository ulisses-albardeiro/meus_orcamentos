# Meus Orçamentos

![Status](https://img.shields.io/badge/Status-Refactoring-yellow)
![PHP](https://img.shields.io/badge/PHP-8.0+-purple)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-blue)

Online management platform for freelancers, independent professionals, and entrepreneurs.

---

## Features

| Module | Description |
|--------|-------------|
| **Quotes** | Create and edit quotes with customized visual identity. |
| **Lists** | Build product or service lists with direct sharing. |
| **Receipts** | Generate personalized digital receipts. |
| **Financial Management** | Track income/expenses, reports, and monthly charts. |
| **Customization** | Add logo, colors, and company information. |

---

**Live:** [meusorcamentos.com.br](https://meusorcamentos.com.br)

---

## 🛠️ Technologies

- **Backend:** PHP 8.0+
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5.3
- **Database:** MySQL 5.7+

---

## ⚡ Quick Installation

```bash
# Clone the repository
git clone https://github.com/seu-usuario/meus_orcamentos.git
cd meus-orcamentos

# Install dependencies
composer install

# Configure environment
cp .env.example .env

# Import database
mysql -u username -p database_name < BD/meusorcamentos.sql