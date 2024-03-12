<?php

namespace App\DataTables;

use App\Models\Tutoring;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TutoringDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $action_code = '<i class="fa fa-eye" style="font-size:16px;color:blue;margin-left: -2px"></i>';
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('action', $action_code);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Tutoring $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('tutoring_table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'        )->title('id'           )->visible(false),
            Column::make('NAME'      )->title('Nombre'       ),
            Column::make('SURNAME'   )->title('Apellidos'    ),
            Column::make('DATE'      )->title('Fecha y hora' ),
            Column::make('created_at')->title('Solicitada'   ),
            Column::make('STATUS'    )->title('Estado'       ),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center')
                  ->title('Ver')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Tutoring_' . date('YmdHis');
    }
}