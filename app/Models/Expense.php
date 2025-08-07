<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel naming conventions)
    protected $table = 'expenses';

    // Mass assignable fields
    protected $fillable = [
        'account_id',
        'category_id',
        'description',
        'amount',
        'date',
        'receipt_path',
    ];

    /**
     * The account associated with the expense.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * The category associated with the expense.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
