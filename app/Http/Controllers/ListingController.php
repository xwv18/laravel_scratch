<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class ListingController extends Controller
{
    //show all listings

    public function index(){
        return view('listings.index',[
        'listings' => Listing::latest()->filter(request(['tag','search']))->paginate(4),
    ]);
    }

    //show single listing

    public function show(Listing $listing){
        return view('listings.show',[
    'listing' => $listing,
    ]);
    }

    // create listing
    public function create(){
        return view('listings.create');
    }

    // store new listing data
    public function newDataStore(Request $request){
       $this->dataValidationCheck($request);
       $dataField = $this->getData($request);
       if($request->hasFile('logo')){
        $dataField['logo'] = $request->file('logo')->store('logos','public');
       }
       $dataField['user_id'] = auth()->id();
       Listing::create($dataField);
        return redirect('/')->with('message', 'Listing created successfully!');
    }

    // listing edit
    public function edit(Listing $listing){
        // Make sure logged in user is owner
        if($listing->user_id != auth()->id()){
            abort(403,'Unauthorized Action');
        }

        return view('listings.edit', ['listing' => $listing]);
    }

    // store update listing data
    public function update(Request $request, Listing $listing){
    //    $this->dataValidationCheck($request);

     // Make sure logged in user is owner
        if($listing->user_id != auth()->id()){
            abort(403,'Unauthorized Action');
        }


       $dataField = $this->getData($request);
       if($request->hasFile('logo')){
        $dataField['logo'] = $request->file('logo')->store('logos','public');
       }
        $listing->update($dataField);
        return back()->with('message', 'Listing updated successfully!');
    }

    //delete listing
    public function delete(Listing $listing){
         // Make sure logged in user is owner
        if($listing->user_id != auth()->id()){
            abort(403,'Unauthorized Action');
        }
        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfully!');
    }

    // manage listing
    public function manage(){
        // dd(auth()->user()->listing()->get());
        return view('listings.manage',['listings' => auth()->user()->listing()->get()]);
    }

    // private function
    private function dataValidationCheck($request){
         Validator::make($request->all(),[
            'title' => 'required',
            'company' => ['required', Rule::unique('listings','company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required',
    ])->validate();
    }
    private function getdata($request){
        return [
            'title' => $request->title,
            'company' => $request->company,
            'location' => $request->location,
            'website' => $request->website,
            'email' => $request->email,
            'tags' => $request->tags,
            'description' => $request->description,
        ];
    }
}