import React from 'react';
import { Head, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardHeader, CardContent, CardFooter } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

export default function ShowTrainingState() {
    const { stats, session, allPassed } = usePage().props;
    return (
        <AppLayout>
            <Head title="Dashboard" />
            <div className="container max-w-5xl mx-auto py-8 px-4 space-y-6">
                <div className="flex flex-col items-center justify-between">
                    <div className="flex items-center">
                        <h1 className="text-2xl font-medium mr-2">Security awareness training results</h1>
                        <Badge variant={allPassed ? 'default' : 'secondary'} className={allPassed ? 'bg-green-500' : undefined}>
                            {allPassed ? 'Finished' : 'Not completed'}
                        </Badge>
                    </div>
                    <h3 className="text-lg text-muted-foreground">
                        {allPassed ? 'Congratulations! You’ve completed all topics.'
                            : 'Please retake the topics you failed in order to pass the training.'}
                    </h3>
                </div>
                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {stats.map((stat) => (
                        <Card key={stat.topic.id}>
                            <CardHeader className="flex justify-between">
                                <span>{stat.topic.name}</span>
                                <Badge variant={stat.passed ? 'success' : 'destructive'}
                                    className={stat.passed ? 'bg-green-500 text-white' : ''}>
                                    {stat.passed ? 'Passed' : 'Failed'}
                                </Badge>
                            </CardHeader>
                            <CardContent>
                                <p>{stat.score}%</p>
                                <p className="text-sm text-muted-foreground">{stat.passed ? 'Great job!' : 'Needs improvement'}</p>
                            </CardContent>
                            {!stat.passed && stat.topic.video_url && (
                                <CardFooter>
                                    <Button size="sm" asChild>
                                        <a href={route('training.questions.watch', { session: session.id, topic: stat.topic.id, })}>
                                            Retake topic test
                                        </a>
                                    </Button>
                                </CardFooter>
                            )}
                        </Card>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
