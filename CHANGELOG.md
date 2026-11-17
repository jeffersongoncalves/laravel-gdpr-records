# Changelog

All notable changes to this project will be documented in this file.

## 1.0.0 - 2026-11-17

First release: GDPR records of processing activities for Laravel, part of the laravel-gdpr suite.

- `ProcessingActivity` stores the Art. 30(1) fields: purpose, lawful basis, data subjects, data categories, recipients, transfers outside the EEA with their Chapter V mechanism, erasure time limits and security measures. Each activity is joined with the `#[PersonalData]` columns of its models.
- `GdprRecords::export()` and `gdpr:ropa [--json]` cover the controller, the EU representative (Art. 27), the DPO and every activity.
- `<x-gdpr-records::dpo />` publishes the DPO contact (Art. 37(7)).
- `<x-gdpr-records::privacy-policy rights-url="..." />` renders a privacy notice (Arts. 13 and 14) from the records: the rights of Arts. 15 to 22, consent withdrawal when consent is relied on, and the supervisory authority to complain to. Texts in English, German, French and Spanish.

## [Unreleased]
