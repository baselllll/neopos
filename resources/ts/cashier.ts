import { BehaviorSubject, forkJoin } from "rxjs";
import { map } from "rxjs/operators";
import { nsHttpClient, nsSnackBar } from "./bootstrap";
import { __ } from "./libraries/lang";

export class Cashier {
    private _mysales: BehaviorSubject<{}>;
    private _reports    =   {
        mysales:    nsHttpClient.get( '/api/nexopos/v4/reports/cashier-report' ),
    };

    constructor() {
        this._mysales           =   new BehaviorSubject<{}>({});

        for( let index in this._reports ) {
            this.loadReport( index );
        }
    }

    loadReport( type ) {
        return this._reports[ type ]
            .subscribe( result => {
                this[ `_${type}` ].next( result );
            })
    }

    refreshReport() {
        nsHttpClient.get( '/api/nexopos/v4/reports/cashier-report?refresh=true' )
            .subscribe( result => {
                this._mysales.next( result );
                nsSnackBar.success( __  ( 'The report has been refreshed.' ), __( 'OK' ) ).subscribe();
            })
    }

    get mysales() {
        return this._mysales;
    }
}

( window as any ).Cashier     =   new Cashier;