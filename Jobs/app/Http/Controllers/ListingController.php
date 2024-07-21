<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

//use Illuminate\Support\Facades\Session;

class ListingController extends Controller
{
    public function index() {
        return view('listings.index', ['pageTitle' => 'Jobs',
            /* 
            - 'listings' => Listing::get() returns a collection object
            - Laravel debugbar is a good tool for developers (dev external extension): composer require barryvdh/laravel-debugbar --dev
            - It has --dev flag as we don't want it to be shown in production. It will be on as long as APP_DEBUG=true in .env file
            - Laravel debugbar can help us detect the duplicated queries count if we have relationships to poll the data we want from the database, we should perform 1 query
            - Eager Load " user " and " SecondRelationship "
            -> Listing::with(['user', 'SecondRelationship'])->paginate(6) (Methods of the class that were defined as relationships are put in an array)
            - Database query won't be duplicated that way if we have relationships
            */
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)->appends(['tag' => request('tag'), 'search' => request('search')])
            /*
            - we can append all quaries by using this function withQueryString(), appends(['search' => request('search')]) or appends($_GET)
            - we can use simplePaginate() to show "previous" and "next" buttons instead of page numbers
            - Listing::latest() is the same as Listing::orderBy('created_at', 'desc')
            */
        ]);
    }

    public function show(Listing $listing) {
    /*
    - We can die and dump to find which namespace is being used with the file directory then we can read what that method really do
    -> dd(Listing::paginate(2)); this will also show the path of the class file that has the paginate method
    */
    /*
    - We can return the created_at or updated_at fields from the database and return it as a carbon object (It's a thrid party date-time manipulation library for PHP)
    -> $listing->created_at->toTimeString() this is being handled through Carbon library
    - One of the good use here is " $listing->created_at->diffForHumans() " which can show how long it took since the database field was created through Carbon library
    */
    /*
    - We can search for a row in database by any field without defining the data type of the paramenter through a model (without route model binding) using this:
    -> $model = Listing::where('id', $listing)->first(); -> for a single row
    -> $model = Listing::where('votes', '>',  $listing)->get(); -> for multiple rows
    -> $model = Listing::where('votes', '>',  $listing)->count(); -> to get the amount of a single row or multiple rows
    - We can also generate the query without the vluent interface using whereRaw
    -> $model = Listing::whereRaw('age > 25 and votes = 100', [25])->get();
    - We can also search directly in database through database facade
    -> $sql = DB::table('listings')->where('id', $listing)->first();
    -> use Illuminate\Support\Facades\DB;
    - We can also list all rows then we can filter through the class model using " scopeFilter " method
    - We can define a custom primary key instead of " id " in the model as models search by id by default according to route model binding:
    -> protected $primaryKey = 'CustomField';
    */
    // $listing = Listing::find($id);
    // if($listing)
    // {
        return view('listings.show', ['pageTitle' => 'GIG', 'value' => $listing]);
    // }
    // else
    //     abort('404');
    }

    public function create() {
        return view('listings.create', ['pageTitle' => 'Post Job']);
    }

    public function store(Request $request) {
        //Other way to validate is $this->validate($request, array)
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'desc' => 'required',
            //'captcha' => 'required|int|min:1000|max:9999',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        if($request->hasFile('logo'))
            $formFields['logo'] = $request->file('logo')->store('logos', 'public'); // this returns the path of where the file was saved

        $formFields['user_id'] = auth()->id();

        // if(!session()->has('captcha') || $request->captcha != session('captcha'))
        //     return redirect('/listings/create')->with('captchaMessage', 'Captcha is not correct!'); // or can use " back()->withErrors(['captcha' => 'Invaled Captcha'])->onlyInput('captcha'); "

        unset($formFields['g-recaptcha-response']);
        Listing::create($formFields);

       // We can either use sessino flash with importing Session Class or we can send it with redirect or we can helper function "session()->flash" instead
       //Session::flash('message', 'Listing created');
       return redirect('/')->with('message', 'Listing Created!');
    }

    public function edit(Listing $listing) {
        /*
        - auth()->id() is the same as auth()->user()->id
        - We can use request()->id() since request functions the same way as auth in this scope
        - We can use policy to prevent users from doing some actions and use " can('ClassMethod', $listing) " directive in blade files to check
        -> authorize->('ClassMethod', $listing) is the policy method that we can use to check if the logged in use can perform some actions
        - We can send an email for tesing using mailtrap website then we can save the smtp settings in .env file under the mail section
        - Creating an email class will help us use the methods in side our controllers
        -> php artisan make:mail PostLiked --markdown=emails.posts.post.liked (app\mail\PostLiked.php)
        - emails.posts.post.liked is blade template which will be placed in (views\emails\posts\post.liked.blade.php)
        - in controller we can define everything and create a mail object
        - UserThatShouldReceiveTheMail = a user object to get the mail from the database to send an email to
        - Mail::class requires Illuminate\Support\Facade\Mail;
        -> Mail::to(UserThatShouldReceiveTheMail)->send(new PostLiked());
        - We should define the parameters to pass through PostLiked class object using a constructor to update public properties such (User liker, Post post)
        - We passed User object (liker) to get the name of the person who liked the post and Post object to add the url to the post along with the email as well
        - To prevent the liker to spam likes we can use soft deleting in database and that can be appied by migrating the database that will have likes (field name: deleted_at)
        - We will need to import softDeletes inside the model class which is in case Like
        -> use HasFactory, SoftDeletes
        - That requires use \Illuminate\Database\Eloquent\SoftDeletes;
        - After that we will be able to use SoftDelete when we delete a row from the database through Route with a DELETE method in models with Eloquent
        - Before we send an email using Mail::class we should check if there is a record in the database that was softly deleted (onlyTrashed or withTrashed)
        -> Listing::withTrashed()->get()
        -> if(!$post->likes()->onlyTrashed->where('user_id', $auth->user()->id)->count())
        - It will return a collection with count of filtered trashed likes then it won't send an email if there is a record that was sofly deleted
        */
        if(auth()->user()->id != $listing->user_id) {
            abort('403', 'Unauthorized Action');
        }

        return view('listings.edit', ['pageTitle' => 'Edit Listing', 'value' => $listing]);
    }

    public function update(Request $request, Listing $listing) { // We can get request data as long as there is a request method that leads to here such as post through route (Route model binding)
        //auth()->id() is the same as auth()->user()->id
        // We can use request()->id() since request functions the same way as auth in this scope
        if(auth()->user()->id != $listing->user_id) {
            abort('403', 'Unauthorized Action');
        }

        //Other way to validate is $this->validate($request, array)
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'desc' => 'required'
        ]);

        if($request->hasFile('logo'))
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');

        $listing->update($formFields);
        return back()->with('message', 'Listing Updated!'); // redirects the user to the last page they visits
    }
    
    public function destory(Listing $listing) {
        //auth()->id() is the same as auth()->user()->id
        // We can use request()->id() since request functions the same way as auth in this scope
        if(auth()->user()->id != $listing->user_id) {
            abort('403', 'Unauthorized Action');
        }

        $listing->delete($listing);
        return redirect('/')->with('message', 'Listing Deleted!');
    }

    public function manage() {
        return view('listings.manage', ['pageTitle' => 'Manage Listings', 'listings' => auth()->user()->listings()->filter(['order' => true])->paginate(5)]);
    }
}
