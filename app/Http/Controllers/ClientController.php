<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\UserCustom;
use App\ClientTask;
use App\ClientCampaign;
use App\BillingMain;
use Carbon\Carbon;
class ClientController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $clients = Client::all();
        return view('client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $item = null;
        $type = 'client';
        $title = 'Add Client';
        $ucustoms = UserCustom::first();
        $labels = getLabels($ucustoms);
        return view('client.show', compact('item', 'type', 'labels', 'title', 'ucustoms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'firstname1' => ['required',],
            'lastname1' => ['required'],
        ]);
        $data = $request->all();
        if(isset($data['birthdate1'])){
            $data['birthdate1'] = Carbon::createFromFormat('m-d-Y', $data['birthdate1']);
        }
        if(isset($data['birthdate2'])){
            $data['birthdate2'] = Carbon::createFromFormat('m-d-Y', $data['birthdate2']);
        }
        if(isset($data['childbirthdate1'])){
            $data['childbirthdate1'] = Carbon::createFromFormat('m-d-Y', $data['childbirthdate1']);
        }
        if(isset($data['childbirthdate2'])){
            $data['childbirthdate2'] = Carbon::createFromFormat('m-d-Y', $data['childbirthdate2']);
        }
        if(isset($data['childbirthdate3'])){
            $data['childbirthdate3'] = Carbon::createFromFormat('m-d-Y', $data['childbirthdate3']);
        }
        if(isset($data['childbirthdate4'])){
            $data['childbirthdate4'] = Carbon::createFromFormat('m-d-Y', $data['childbirthdate4']);
        }
        if ($data['relatedclient_id'] == 'none')
            $data['relatedclient_id'] = null;
        Client::create($data);

        return redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client) {
        return $this->edit($client);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client) {
        $item = $client;
        $tasks = ClientTask::where('contactclientmain_id' , $client->id)->get();
        $emails = ClientCampaign::where('contactclientmain_id' , $client->id)->get();
        $campaigns = ClientCampaign::where('contactclientmain_id' , $client->id)->whereNotNull('sentdate')->get();
        $billings = BillingMain::where('contactclient_id' , $client->id)->get();
        $type = 'client';
        $title = 'View / Edit Client';
        $ucustoms = UserCustom::first();
        $labels = getLabels($ucustoms);
        return view('client.show', compact('item', 'type', 'labels', 'title', 'ucustoms', 'tasks', 'emails', 'campaigns', 'billings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client) {

        $data = $request->except(['_method', '_token']);

        if (isset($data['relatedclient_id'])) {
            if ($data['relatedclient_id'] == 'none')
                $data['relatedclient_id'] = null;
            $client->relatedclient_id = $data['relatedclient_id'];
        }
        $client->save();
        
        if(isset($data['birthdate1'])){
            $data['birthdate1'] = Carbon::createFromFormat('m-d-Y', $data['birthdate1']);
        }
        if(isset($data['birthdate2'])){
            $data['birthdate2'] = Carbon::createFromFormat('m-d-Y', $data['birthdate2']);
        }
        if(isset($data['childbirthdate1'])){
            $data['childbirthdate1'] = Carbon::createFromFormat('m-d-Y', $data['childbirthdate1']);
        }
        if(isset($data['childbirthdate2'])){
            $data['childbirthdate2'] = Carbon::createFromFormat('m-d-Y', $data['childbirthdate2']);
        }
        if(isset($data['childbirthdate3'])){
            $data['childbirthdate3'] = Carbon::createFromFormat('m-d-Y', $data['childbirthdate3']);
        }
        if(isset($data['childbirthdate4'])){
            $data['childbirthdate4'] = Carbon::createFromFormat('m-d-Y', $data['childbirthdate4']);
        }

        Client::where('id', $client->id)->update($data);

        return redirect()->route('clients.edit', ['client' => $client->id])->with('success', "Client updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client) {
        return redirect()->route('clients.index')->with('error', "No deleting allowed.  Make the record Inactive to appear at bottom of list.");
    }

}
