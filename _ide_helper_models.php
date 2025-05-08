<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $cif_nif
 * @property string $name
 * @property string $address
 * @property string|null $number
 * @property string|null $letter
 * @property string|null $staircase
 * @property string|null $phone
 * @property string|null $town_municipality
 * @property string|null $province
 * @property string|null $postal_code
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCifNif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereStaircase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereTownMunicipality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
 */
	class Client extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $invoice_id
 * @property int $client_id
 * @property int|null $representative_id
 * @property int $liquidation_id
 * @property int $user_id
 * @property string|null $invoice_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\Liquidation $liquidation
 * @property-read \App\Models\Client|null $representative
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereLiquidationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereRepresentativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUserId($value)
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $file_number
 * @property \Illuminate\Support\Carbon $liquidation_date
 * @property string|null $concept
 * @property numeric|null $taxable_base
 * @property numeric|null $tax_rate
 * @property numeric|null $total_fee
 * @property numeric|null $bond
 * @property numeric|null $total_to_pay
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereBond($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereConcept($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereFileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereLiquidationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereTaxableBase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereTotalFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereTotalToPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Liquidation whereUpdatedAt($value)
 */
	class Liquidation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $bank_name
 * @property string|null $account_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Liquidation> $liquidations
 * @property-read int|null $liquidations_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUpdatedAt($value)
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $file_number
 * @property string|null $concept
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proceeding newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proceeding newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proceeding query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proceeding whereConcept($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proceeding whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proceeding whereFileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proceeding whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Proceeding whereUpdatedAt($value)
 */
	class Proceeding extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

