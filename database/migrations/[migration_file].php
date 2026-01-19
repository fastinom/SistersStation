// WRONG ❌
Schema::hasTable(['table_name'])
Schema::create(['users'], function...)

// CORRECT ✅
Schema::hasTable('table_name')
Schema::create('users', function...)
