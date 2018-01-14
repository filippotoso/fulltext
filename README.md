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
        // Create a fulltext index in the users table for the first_name and last_name columns
        Fulltext::fullTextIndex('users', ['first_name', 'last_name']);

        // You can also specify a name for the index with the third parameter
        Fulltext::fullTextIndex('users', ['city', 'address', 'country'], 'search_address');

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
            $table->dropIndex('search_address');
        });

    }

}

```

Enable fulltext functions within the User model (for instance):

```
namespace App;

use Illuminate\Database\Eloquent\Model;
use FilippoToso\FullText\Traits\FullText;

class User extends Model
{
    use FullText;
}
```

Use the FullText trait's features:

```
// Use the whereMatch() scope to search for all users named "filippo"
$users = App\User::select('*')
                ->whereMatch(['first_name', 'last_name'], 'filippo')
                ->get();

// Include in the result the score column with the matching score
$users = App\User::select('*')
                ->whereMatch(['first_name', 'last_name'], 'filippo')
                ->selectMatchScore(['first_name', 'last_name'], 'filippo')
                ->get();

// You can also include the name of the score column with the third parameter of selectMatchScore()
$users = App\User::select('*')
                ->whereMatch(['first_name', 'last_name'], 'filippo')
                ->selectMatchScore(['first_name', 'last_name'], 'filippo', 'matching_score')
                ->get();

```
