# WooCommerce Role-based Order Status (WROS)

Ce plugin permet de définir automatiquement le statut des commandes WooCommerce selon le **rôle utilisateur** et éventuellement le **mode de paiement**.

## 🎯 Objectif
Différencier les clients **professionnels** et **particuliers** pour faciliter le suivi des paiements et des relances.

## ⚙️ Fonctionnalités
- Mapping **Rôle → Statut** (ex. wholesaler → pending).
- Mapping **Rôle × Méthode de paiement → Statut** (ex. customer × invoice → on-hold).
- Interface d’administration simple dans WooCommerce.
- Compatibilité complète avec tous les statuts WooCommerce (`pending`, `processing`, `on-hold`, `completed`, etc.).
- Pas de modification des commandes déjà payées.

## 🧱 Installation
1. Télécharge le dossier `woocommerce-role-order-status` dans `wp-content/plugins/`.
2. Active le plugin dans l’admin WordPress.
3. Configure les mappings dans **WooCommerce → Settings → Role Order Status**.

## 🧩 Exemples
- **Client pro** (rôle : `wholesaler`, paiement : facture) → statut `pending`.
- **Client particulier** (rôle : `customer`, paiement : facture) → statut `on-hold`.
- **Paiement par carte** → comportement standard de WooCommerce.

## 🔒 Licence
GPL-2.0 or later  
© Fermentierra — libre d’utilisation, de modification et de distribution sous la même licence.
