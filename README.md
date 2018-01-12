# Fulltext

A simple way to add fulltext capabilities to your Eloquent models (just for MySQL for the moment).

## Requirements

- PHP 5.6+
- Laravel 5.0+

## Installing

Use Composer to install it:

```
composer require filippo-toso/fulltext
```

## Using It

```
namespace App;

use Illuminate\Database\Eloquent\Model;
use FilippoToso\FullText\Traits\FullText;

class Flight extends Model
{
    use FullText;
}

// Search for


```

Create a fulltext index in your migrations:

```
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use FilippoToso\Fulltext\Fulltext;

class AddIndexesToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

         

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['first_name', 'last_name']);
        });

    }
}

```
